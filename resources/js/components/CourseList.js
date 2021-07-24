import React, { useEffect } from 'react';


function CourseList(props) {
    console.log("init courseList");

    const selectCourse = (id) => {
        console.log('course item clicked: ', id)
        props.selectCourse(id);
    }

    const courseItems = props.courses.map((course) => {
        // console.log("course: ", course);
        if ( props.selectedCourse && course.id === props.selectedCourse.id) {
            console.log("props.selectedCourse: ", props.selectedCourse);
        }

        return (
            <div key={course.id} className={(props.selectedCourse && props.selectedCourse.id === course.id )? "course-list-row selected" : "course-list-row" }
            onClick={() => selectCourse(course.id)}>
                <div className="course-list-label">{course.abbreviation}</div>
            </div>
        )
    })
    
    return (
        <div id="center-right">
            <div id="course-list-header">Degree Courses</div>
            <div id='course-list' >
                {courseItems}
            </div>
        </div>
    );
}

export default CourseList;