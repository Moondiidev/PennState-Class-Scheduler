import React, { useState, useEffect } from "react";
import BarChart from "./BarChart";
import CourseModel from "./Model/Courses";
import ReactDOM from "react-dom";

import CourseMap from "./CourseMap";
import CourseList from "./CourseList";
import CourseInspector from "./CourseInspector";
import CourseControls from "./CourseControls";
import DegreeProgress from "./DegreeProgress";


/**
 * Main controller for the app
 * 
 * @author Mark Westerlund
 * @version 1.0
 * @returns 
 */
function AppController() {

    const [selectedCourse, setSelectedCourse] = useState(null);
    const [courses, setCourses] = useState([]);
    const [courseTypes, setCourseTypes] = useState([]);

    /**
     * runs once when loading, this forces load of courses once the app is initialized
     */
    useEffect(() => {
        CourseModel.loadCourses(() => {
            let loadedCourses = CourseModel.getAllCourses();

            // console.log("loaded courses in appcontroller", loadedCourses)

            setCourses(loadedCourses);

        });
    }, []);

    /**
     * Sets the selected course
     * @param {String} id Id of course
     */
    const selectCourse = (id) => {
        console.log("set selectedCourse", id);

        if (selectedCourse === null) {
            setSelectedCourse(CourseModel.getCourseById(id));
        } else {
            if (selectedCourse.id === id) {
                setSelectedCourse(null);
            } else {
                setSelectedCourse(CourseModel.getCourseById(id));
            }
        }
    };

    /**
     * Sets filter params
     * TODO: GET WORKING!
     * @param {Array} params 
     */
    const setFilter = (params) => {
        console.log("params: ", params);
    };

    /**
     * returns the app jsx structure
     */
    return (
        <div className="mark-test-style">
            <div id="course-view-top">
                <div className="left">
                    <CourseMap 
                        selectedCourse={selectedCourse}
                        selectCourse={selectCourse}
                    />
                    <DegreeProgress
                        courses={courses}
                        courseBins={CourseModel.getCourseBins()}
                        degreeCompletion={CourseModel.getDegreeCompletion()}
                    />
                </div>

                <CourseList
                    courses={courses}
                    selectedCourse={selectedCourse}
                    selectCourse={selectCourse}
                />
            </div>
            <div id="course-view-bottom">
                <CourseInspector
                    selectedCourse={selectedCourse}
                />
                <CourseControls courseTypes={courseTypes} />
            </div>
        </div>
    );
}
export default AppController;

if (document.getElementById("myTest")) {
    ReactDOM.render(
        <React.StrictMode>
            <AppController />
        </React.StrictMode>,
        document.getElementById("myTest")
    );
}
