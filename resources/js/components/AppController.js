import React, { useState } from "react";
import BarChart from "./BarChart";
import CourseModel from "./Model/Courses";
import ReactDOM from "react-dom";

import CourseMap from "./CourseMap";
import CourseList from "./CourseList";
import CourseInspector from "./CourseInspector";
import CourseControls from "./CourseControls";

function AppController() {
    const model = new CourseModel();

    model.sortCourses();

    const courses = model.getAllCourses();
    const courseTypes = model.getCourseTypes();

    const [selectedCourse, setSelectedCourse] = useState(null);

    console.log("courses: ", courses);
    console.log("courseTypes: ", courseTypes);

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
                <CourseMap
                    courses={courses}
                    selectedCourse={selectedCourse}
                    selectCourse={selectCourse}
                    courseNodes={model.getCourseNodes()}
                    courseLinks={model.getCourseLinks()}
                />

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
    // find element by id
    const element = document.getElementById("courses");

    // create new props object with element's data-attributes
    const props = Object.assign({}, element.dataset.props);

    ReactDOM.render(
        <React.StrictMode>
            <AppController />
        </React.StrictMode>,
        document.getElementById("myTest")
    );
}
