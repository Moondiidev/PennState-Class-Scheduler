import React, { useEffect } from "react";

function CourseList(props) {
    console.log("init courseList", props);

    const selectCourse = (id) => {
        console.log("course item clicked: ", id);
        props.selectCourse(id);
    };

    const courseItems = props.courses.map((course) => {
        // console.log("course: ", course);
        if (props.selectedCourse && course.id === props.selectedCourse.id) {
            console.log("props.selectedCourse: ", props.selectedCourse);
        }

        return (
            <div
                key={course.id}
                className={
                    props.selectedCourse &&
                    props.selectedCourse.id === course.id
                        ? "course-list-row selected-row"
                        : "course-list-row"
                }
                onClick={() => selectCourse(course.id)}
            >
                <div className={
                    props.selectedCourse &&
                    props.selectedCourse.id === course.id
                        ? "course-list-id selected"
                        : "course-list-id"
                }>{course.abbreviation}</div>
                <div className={
                    props.selectedCourse &&
                    props.selectedCourse.id === course.id
                        ? "course-list-title selected"
                        : "course-list-title"
                }>{course.title}</div>
            </div>
        );
    });

    return (
        <div className="right">
            <div className="font-semibold bg-gray-200 text-gray-700 py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">Degree Courses</div>
            <div id="course-list">{courseItems}</div>
        </div>
    );
}

export default CourseList;
