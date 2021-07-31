import React, { useState, useEffect } from "react";
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
function AppController(props) {
    const [selectedCourse, setSelectedCourse] = useState(null);
    const [filterSettings, setFilterSettings] = useState({});
    const [courses, setCourses] = useState([]);

    console.log("completed courses: " + props.completed);

    // set courses in state on initial load
    useEffect(() => {
        setCourses(CourseModel.loadCourses(JSON.parse(props.courses)));
        const courseTypes = CourseModel.getCourseTypes(courses);
        // set initial filter settings
        setFilterSettings({ ...filter.getFilterSettings() });
    }, [])

    /**
     * Sets the selected course
     * @param {Array} courses courses
     * @param {String} id Id of course
     */
    const selectCourse = (id) => {
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
     * Sets filters
     * @param {String} type
     * @param {Object} params
     */
    const setFilter = (type, params) => {
        console.log("SET FILTER: ", type, params);

        filter.setFilter(type, params);

        const filteredCourses = filter.runFilter(courses);

        setCourses(filteredCourses)

        console.log(
            "SET FILTER SETTINGS: ",
            filter.getFilterSettings(),
            filterSettings
        );
        setFilterSettings({ ...filter.getFilterSettings() });
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
                        degreeCompletion={CourseModel.getDegreeCompletion(
                            courses
                        )}
                    />
                </div>

                <CourseList
                    courses={courses}
                    selectedCourse={selectedCourse}
                    selectCourse={selectCourse}
                />
            </div>
            <div id="course-view-bottom">
                <CourseInspector selectedCourse={selectedCourse} />
                <CourseControls
                    filterSettings={filterSettings}
                    setFilter={setFilter}
                    // courseTypes={courseTypes}
                />
            </div>
        </div>
    );
}
export default AppController;

if (document.getElementById("myTest")) {
    const courses = document.getElementById("myTest").getAttribute("data");
    const completed = document.getElementById("myTest").getAttribute("data-courses");

    ReactDOM.render(
        <React.StrictMode>
            <AppController courses={courses} completed={completed}/>
        </React.StrictMode>,
        document.getElementById("myTest")
    );
}
