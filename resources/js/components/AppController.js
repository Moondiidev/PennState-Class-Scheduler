import React, { useState } from "react";
import BarChart from "./BarChart";
import CourseModel from "./Model/Courses";
import ReactDOM from "react-dom";

import CourseMap from "./CourseMap";
import CourseList from "./CourseList";

function AppController(props) {

    const model = new CourseModel();

    model.sortCourses();

    const courses = model.getAllCourses();

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

    // find element by id
    const element = document.getElementById('courses')

    // create new props object with element's data-attributes
    const props = Object.assign({}, element.dataset.props)

    ReactDOM.render(
        <React.StrictMode>
            <AppController{...props} />
        </React.StrictMode>,
        document.getElementById("myTest")
    );
}
