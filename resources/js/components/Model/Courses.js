import axios from "axios";
import * as d3 from "d3";

let courses = require("./data/markTest.json");

function model() {
    let useServer = true;
    let courseLevelAscending = true;

    let courseTypes = [];

    function loadCourses(callback) {
        if (useServer) {
            console.log("using server for course data");

            axios({
                method: "get",
                url: "/api/courses",
            })
                .then((response) => {
                    console.log("response from courses: ", response.data);
                    // return response.data;

                    courses = response.data.map((course) => {
                        course.isCompleted = false;
                        course.inFilter = true;
                        return course;
                    });

                    callback(courses);
                })
                .catch(function (error) {
                    console.log(error.toJSON());
                });
        }

        //  this.courses = require("./data/markTest.json");
        // return this.courses;
    }

    const getCourseById = (id) => {
        console.log("courses: ", courses);
        for (let index = 0; index < courses.length; index++) {
            if (courses[index].id === id) {
                return courses[index];
            }
        }
    };

    const processCourses = () => {
        courses.forEach((course) => {
            course.childCourses = [];
            course.inFilter = true;

            course.isCompleted = Math.random() > 0.5;

            if (!course.prerequisites) {
                course.prerequisites = [];
            }
            course.prerequisites = course.prerequisites.map((courseId) => {
                let prereq = getCourseById(courseId);

                if (!prereq.childCourses) {
                    prereq.childCourses = [];
                }

                prereq.childCourses.push(course);

                return prereq;
            });

            if (!course.concurrents) {
                course.concurrents = [];
            }

            course.concurrents = course.concurrents.map((courseId) => {
                let concur = getCourseById(courseId);

                return concur;
            });
        });
    };

    const sortCourses = () => {
        courses.sort((a, b) => {
            let courseLevelA = Number(a.abbreviation.match(/\d+/g)[0]);
            let courseLevelB = Number(b.abbreviation.match(/\d+/g)[0]);

            // console.log("abr A", courseLevelA[0], a.abbreviation)
            // console.log("abr B", courseLevelB[0], b.abbreviation)

            if (courseLevelAscending) {
                return d3.ascending(courseLevelA, courseLevelB);
            } else {
                return d3.descending(courseLevelA, courseLevelB);
            }

            // return courseLevelA[0] - courseLevelB[0];
        });
    };

    const getCourseTypes = () => {
        let types = {};
        courses.forEach((course) => {
            if (!types[course.type]) {
                types[course.type] = 0;
            }
            types[course.type]++;
        });

        console.log("courseTypes counts: ", types);

        courseTypes = [];
        for (let type in types) {
            courseTypes.push(type);
        }

        return courseTypes;
    };

    const getCourseBins = () => {
        let bins = {};
        courses.forEach((course) => {
            if (!bins[course.type]) {
                bins[course.type] = {
                    type: course.type,
                    completed: 0,
                    incomplete: 0,
                    total: 0,
                };
            }

            if (course.isCompleted) {
                bins[course.type].completed++;
            } else {
                bins[course.type].incomplete++;
            }
            bins[course.type].total++;
        });

        console.log("courseTypes counts: ", bins);

        let courseBins = [];
        for (let type in bins) {
            courseBins.push(bins[type]);
        }

        return courseBins;
    };

    const getDegreeCompletion = () => {
        let total = 0;
        let completed = 0;

        courses.forEach((course) => {
            total += course.credits;
            if (course.isCompleted) {
                completed += course.credits;
            }
        });

        let incomplete = total - completed;

        return {
            total: total,
            completed,
            incomplete: incomplete,
            pie: [
                {
                    key: "completed",
                    value: completed,
                },
                {
                    key: "incomplete",
                    value: incomplete,
                },
            ],
        };
    };

    const getAllCourses = () => {
        return courses;
    };

    const getCourseNodes = () => {
        return courses; // may have to change???
    };

    const getCourseLinks = () => {
        const links = [];

        courses.forEach((course) => {
            if (!course.prerequisites) {
                course.prerequisites = [];
            }

            course.prerequisites.forEach((preReqId) => {
                const link = {
                    source: preReqId,
                    target: course.id,
                };

                links.push(link);
            });
        });

        return links;
    };

    return {
        loadCourses,
        getCourseById,
        getAllCourses,
        getCourseNodes,
        getCourseLinks,
        sortCourses,
        processCourses,
        getCourseTypes,

        getCourseBins,
        getDegreeCompletion,
    };
}

export default model;
