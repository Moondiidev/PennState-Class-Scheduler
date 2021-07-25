import React, { useState, useEffect } from "react";
import BarChart from "./BarChart";
import CourseModel from "./Model/Courses";
import ReactDOM from "react-dom";

import CourseMap from "./CourseMap";
import CourseList from "./CourseList";
import CourseInspector from "./CourseInspector";
import CourseControls from "./CourseControls";
import { mode } from "d3";
import DegreeProgress from "./DegreeProgress";

function AppController() {
    const model = new CourseModel();

    const [selectedCourse, setSelectedCourse] = useState(null);
    const [dataLoaded, setDataLoaded] = useState(false);
    const [courses, setCourses] = useState([]);
    const [courseTypes, setCourseTypes] = useState([]);



    // useEffect(() => {
    //     model.loadCourses(() => {
    //         model.sortCourses();

    //         let loadedCourses = model.getAllCourses();

    //         console.log("loadedCourses in callback: ", loadedCourses);

    //         setCourses(...loadedCourses);
    //         setCourseTypes(...model.getCourseTypes());

    //         model.setCourses(courses);
    //     });
    // }, []);

    useEffect(() => {
        model.processCourses();
        model.sortCourses();
        setCourses(model.getAllCourses());
        setCourseTypes(model.getCourseTypes());

    }, []);


    const selectCourse = (id) => {
        console.log("set selectedCourse", id);

        if (selectedCourse === null) {
            setSelectedCourse(model.getCourseById(id));
        } else {
            if (selectedCourse.id === id) {
                setSelectedCourse(null);
            } else {
                setSelectedCourse(model.getCourseById(id));
            }
        }
    };

    const setFilter = (params) => {
        console.log("params: ", params);
    };

    return (
        <div className="mark-test-style">
            <div id="course-view-top">
                {/* <CourseMap
                    courses={courses}
                    selectedCourse={selectedCourse}
                    selectCourse={selectCourse}
                    courseNodes={model.getCourseNodes()}
                    courseLinks={model.getCourseLinks()}
                /> */}
                <div className="left">
                    <CourseMap />
                    <DegreeProgress
                        courses={courses}
                        courseBins={model.getCourseBins()}
                        degreeCompletion={model.getDegreeCompletion()}
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
                    model={model}
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
