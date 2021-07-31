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

    const loadCourses = (courses) => {
        courses.map((course) => {
            course.isCompleted = false;
            course.inFilter = true;
            return course;
        });

        processCourses(courses);
        sortCourses(courses);

        return courses;
    };

    /**
     * Finds the course in the course array
     * @param {Array} courses courses
     * @param {String} id id of course
     * @returns course
     */
    const getCourseById = (courses, id) => {
        for (let index = 0; index < courses.length; index++) {
            if (courses[index].id == id) {
                return courses[index];
            }
        }
    };

    /**
     * Processes loaded courses for proper viewing
     */
    const processCourses = (courses) => {
        courses.map((course) => {
            course.childCourses = [];
            course.inFilter = true;

            course.isCompleted = Math.random() > 0.5;

            course.level = Number(course.abbreviation.match(/\d+/g)[0]);

            if (!course.prerequisites) {
                course.prerequisites = [];
            } else {

                course.prerequisites = course.prerequisites.map((courseId) => {
                    let prereq = getCourseById(courses, courseId);

                    if (!prereq.childCourses) {
                        prereq.childCourses = [];
                    }

                    prereq.childCourses.push(course);

                    return prereq;
                });
            }


            if (!course.concurrents) {
                course.concurrents = [];
            } else {

                course.concurrents = course.concurrents.map((courseId) => {
                    let concur = getCourseById(courses, courseId);

                    if (!concur.childCourses) {
                        concur.childCourses = [];
                    }

                    concur.childCourses.push(course);

                    return concur;
                });
            }


            return course;
        });
    }

    /**
     * Sorts courses according to a heuristic that higher courses should be taken after lower numbered courses
     */
    const sortCourses = (courses) => {
        courses.sort((a, b) => {
            let courseLevelA = a.level;
            let courseLevelB = b.level;

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
    const getCourseTypes = (courses) => {
        let types = {};
        courses.forEach((course) => {
            if (!types[course.type]) {
                types[course.type] = 0;
            }
            types[course.type]++;
        });

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
    const getCourseBins = (courses) => {
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

        //console.log("courseTypes counts: ", bins);

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
    const getDegreeCompletion = (courses) => {
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
     * Returns methods that should be public, all other methods and fields are private to the model
     */
    return {
        loadCourses,
        getCourseById,
        sortCourses,
        processCourses,
        getCourseTypes,
        getCourseBins,
        getDegreeCompletion,
    };
}

export default model();

