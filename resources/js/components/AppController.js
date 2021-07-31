import React, { useState, useEffect } from "react";
import BarChart from "./BarChart";
import CourseModel from "./Model/Courses";
import ReactDOM from "react-dom";

import CourseMap from "./CourseMap";
import CourseList from "./CourseList";
import CourseInspector from "./CourseInspector";
import CourseControls from "./CourseControls";
import DegreeProgress from "./DegreeProgress";

import filter from "./Model/Filter";


/**
 * Main controller for the app
 * 
 * @author Mark Westerlund
 * @version 1.0
 * @returns 
 */
function AppController() {

    console.log("filter: ", filter)

    const [selectedCourse, setSelectedCourse] = useState(null);
    const [courses, setCourses] = useState([]);
    const [courseTypes, setCourseTypes] = useState([]);
    const [filterSettings, setFilterSettings] = useState({});
    const [updateDisplay, setUpdateDisplay] = useState(0);

    /**
     * runs once when loading, this forces load of courses once the app is initialized
     */
    useEffect(() => {
        CourseModel.loadCourses(() => {
            let loadedCourses = CourseModel.getAllCourses();

            setFilterSettings({...filter.getFilterSettings()});

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
     * Sets filters
     * @param {String} type 
     * @param {Object} params 
     */
    const setFilter = (type, params) => {
        console.log("SET FILTER: ", type, params);

        filter.setFilter(type, params);

        filter.runFilter(CourseModel.getAllCourses());
        setCourses(CourseModel.getAllCourses());

        console.log("SET FILTER SETTINGS: ", filter.getFilterSettings(), filterSettings)
        setFilterSettings({...filter.getFilterSettings()});
    }

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
                <CourseControls 
                filterSettings={filterSettings}
                setFilter={setFilter}
                courseTypes={courseTypes}
                 />
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
