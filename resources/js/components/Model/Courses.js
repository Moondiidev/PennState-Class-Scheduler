import axios from "axios";

const courses = require("./data/markTest.json");

function model() {

    let useServer = false;
    
    // let courses = [];
    

    // const loadCourses = () => {

    //     if (useServer) {

    //         axios.get({
    //             method: 'get',
    //             url: '/courses',
    //         }).then((response) => {
    //             console.log("response from courses: ", response);
                
    //         })
    //     } else {
    //         courses = require("./data/markTest.json");
    //     }
    // }

    const getCourseById = (id) => {

        for (let index = 0; index < courses.length; index++) {
            if (courses[index].id === id) {
                return courses[index];
            }
        }

    }

    const getAllCourses = () => {
        return courses;
    }

    const getCourseNodes = () => {
        return courses; // may have to change???
    }

    const getCourseLinks = () => {
        const links = [];

        courses.forEach((course) => {
            if (!course.prerequisites) {
                course.prerequisites = [];
            }

            course.prerequisites.forEach((preReqId) => {
                const link = {
                    source: preReqId,
                    target: course.id
                }

                links.push(link);
            })
        })

        return links;

    }

    return {
        // loadCourses,
        getCourseById,
        getAllCourses,
        getCourseNodes,
        getCourseLinks
    }

}

export default model;