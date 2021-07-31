import * as d3 from "d3";

// let courses = require("./data/markTest.json");

/**
 * Main course model
 * 
 * @author Mark Westerlund
 * @version 1.0
 * 
 * @returns 
 */
function model() {
    let courseLevelAscending = true;

    let courseTypes = [];
    let courses = [];

    /**
     * Loads courses from the server
     * @param {Function} callback 
     */
    function loadCourses(callback) {
        console.log("using server for course data");

        fetch("/api/courses").then((response) => {
                console.log("response from courses: ", response.data);
                // return response.data;

                return response.json();
            })
            .then((results) => {
                console.log("test test results: ", results)
                courses = results[0].map((course) => {
                    // console.log("test ")
                    course.isCompleted = false;
                    course.inFilter = true;
                    return course;
                });

                processCourses();
                sortCourses();

                callback(courses);
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    /**
     * Finds the course in the course array
     * @param {String} id id of course
     * @returns course
     */
    const getCourseById = (id) => {
        for (let index = 0; index < courses.length; index++) {
            if (courses[index].id === id) {
                return courses[index];
            }
        }
        return null // did not find course with id
    };

    /**
     * Processes loaded courses for proper viewing
     */
    const processCourses = () => {
        console.log("processCourses", courses);
        courses = courses.map((course) => {
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

                if (!concur.childCourses) {
                    concur.childCourses = [];
                }

                concur.childCourses.push(course);

                return concur;
            });

            if (course.concurrents.length > 1) {
                console.log("THIS COURSE HAS MORE THAN 1 CONCURRENTS!!!!!!!!!! ", course)
            }

            return course;
        });
    }

    /**
     * Sorts courses according to a heuristic that higher courses should be taken after lower numbered courses
     */
    const sortCourses = () => {
        courses.sort((a, b) => {
            let courseLevelA = Number(a.abbreviation.match(/\d+/g)[0]);
            let courseLevelB = Number(b.abbreviation.match(/\d+/g)[0]);

            if (courseLevelAscending) {
                return d3.ascending(courseLevelA, courseLevelB);
            } else {
                return d3.descending(courseLevelA, courseLevelB);
            }
        });
    };

    /**
     * Finds all course types
     * @returns course types array
     */
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

    /**
     * Builds bins for course progress
     * @returns Course bins for progress area
     */
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

    /**
     * Builds object for course completion
     * @returns degree completion object
     */
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

    /**
     * Getter for course array
     * @returns Array of Courses
     */
    const getAllCourses = () => {
        return courses;
    };

    /**
     * Returns methods that should be public, all other methods and fields are private to the model
     */
    return {
        loadCourses,
        getCourseById,
        getAllCourses,
        sortCourses,
        processCourses,
        getCourseTypes,

        getCourseBins,
        getDegreeCompletion,
    };
}

export default model();
