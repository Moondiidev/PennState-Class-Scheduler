import axios from "axios";
import * as d3 from "d3";

function model() {

    let useServer = true;
    let courseLevelAscending = true;

    const courses = [];

    const loadCourses = () => {

        if (useServer) {

            console.log("using server for course data");

            axios( {
                method: 'get',
                url: 'https://pennstate-class-scheduler.test/api/courses',
            }).then((response) => {
                console.log("response from courses: ", response.data);
                return response.data;

            }).catch(function (error) {
                console.log(error.toJSON());
            });
        }

         this.courses = require("./data/markTest.json");
        return this.courses;

    }


    const getCourseById = (id) => {
        for (let index = 0; index < courses.length; index++) {
            if (courses[index].id === id) {
                return courses[index];
            }
        }
    };

    const sortCourses = () => {
        courses.sort((a, b) => {
            let courseLevelA = Number(a.abbreviation.match(/\d+/g)[0]);
            let courseLevelB = Number(b.abbreviation.match(/\d+/g)[0]);

            console.log("abr A", courseLevelA[0], a.abbreviation)
            console.log("abr B", courseLevelB[0], b.abbreviation)

            if (courseLevelAscending) {
                return d3.ascending(courseLevelA, courseLevelB)
            } else {
                return d3.descending(courseLevelA, courseLevelB)
            }

            // return courseLevelA[0] - courseLevelB[0];
        });
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
    };
}

export default model;
