import React, { useState, useEffect } from "react";
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
function AppController(props) {

    const [selectedCourse, setSelectedCourse] = useState(null);
    const courses = CourseModel.loadCourses(JSON.parse(props.courses));
    const courseTypes = CourseModel.getCourseTypes(courses);

    /**
     * Sets the selected course
     * @param {Array} courses courses
     * @param {String} id Id of course
     */
    const selectCourse = (courses, id) => {

        if (selectedCourse === null) {
            setSelectedCourse(CourseModel.getCourseById(courses, id));
        } else {
            if (selectedCourse.id === id) {
                setSelectedCourse(null);
            } else {
                setSelectedCourse(CourseModel.getCourseById(courses, id));
            }
        }
    };

    /**
     * Sets filter params
     * TODO: GET WORKING!
     * @param {Array} params
     */
    const setFilter = (params) => {
        //console.log("params: ", params);
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
                        courseBins={CourseModel.getCourseBins(courses)}
                        degreeCompletion={CourseModel.getDegreeCompletion(courses)}
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

    const courses = document.getElementById("myTest").getAttribute('data');

    ReactDOM.render(
        <React.StrictMode>
            <AppController courses={courses}/>
        </React.StrictMode>,
        document.getElementById("myTest")
    );
}
