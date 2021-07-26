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
    const { courses, isLoading } = model.loadCourses();

    console.log(courses);


    //const [courses, setCourses] = useState([]);
    const [selectedCourse, setSelectedCourse] = useState(null);
    const [dataLoaded, setDataLoaded] = useState(false);
    const [courseTypes, setCourseTypes] = useState([]);


    useEffect(() => {
        //setCourses(model.getAllCourses());
        model.processCourses();
        model.sortCourses();
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

    if (isLoading)
        return(
            <div>Loading...</div>
        )

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
                    <CourseMap
                        selectedCourse={selectedCourse}
                        selectCourse={selectCourse}
                    />
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
