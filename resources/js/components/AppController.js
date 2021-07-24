import React, { useState } from "react";
import BarChart from "./BarChart";
import CourseModel from "./Model/Courses";
import ReactDOM from "react-dom";

import CourseMap from "./CourseMap";
import CourseList from "./CourseList";
import Courses from "./Model/Courses";

function AppController() {

    const model = new CourseModel();

    model.sortCourses();

    // const courses = model.getAllCourses();
    const courses = model.loadCourses();

    const [selectedCourse, setSelectedCourse] = useState(null);


    console.log("courses: ", courses);

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

    return (
        <div className="mark-test-style">
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
