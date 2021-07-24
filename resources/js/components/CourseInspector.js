import React, { useEffect } from "react";

function CourseInspector(props) {
    console.log("init courseList");

    const prereqItems = (props) => {
        console.log("show prereqs");
        if (
            props.selectedCourse &&
            Array.isArray(props.selectedCourse.prerequisites) &&
            props.selectedCourse.prerequisites.length > 0
        ) {
            return props.selectedCourse.prerequisites.map((courseId) => {
                let course = props.model.getCourseById(courseId);

                return (
                    <div
                        key={courseId}
                        className="related-course-row text-gray-700"
                    >
                        {course.abbreviation}
                    </div>
                );
            });
        } else {
            return <div className="related-course-row text-gray-700">None</div>
        }
    };

    const concurrentItems = (props) => {
        console.log("show concurrents");
        if (
            props.selectedCourse &&
            Array.isArray(props.selectedCourse.concurrents) &&
            props.selectedCourse.prerequisites.length > 0
        ) {
            return props.selectedCourse.concurrents.map((courseId) => {
                let course = props.model.getCourseById(courseId);

                return (
                    <div
                        key={courseId}
                        className="related-course-row text-gray-700"
                    >
                        {course.abbreviation}
                    </div>
                );
            });
        } else {
            return <div className="related-course-row text-gray-700">None</div>
        }
    };

    return (
        <div className="left">
            <div id="inspector-selected-course">
                {props.selectedCourse
                    ? props.selectedCourse.abbreviation
                    : "Select Course"}
            </div>
            <div className="inspector-selected-area grid grid-cols-2 gap-4">
                <div className="container">
                    <div className="inspector-row grid grid-cols2 gap-2">
                        <div className="inspector-data-label text-gray-500">
                            Title:
                        </div>
                        <div className="inspector-data-value text-gray-700">
                            {props.selectedCourse
                                ? props.selectedCourse.title
                                : ""}
                        </div>
                    </div>
                    <div className="inspector-row grid grid-cols2 gap-2">
                        <div className="inspector-data-label text-gray-500">
                            Description:
                        </div>
                        <div className="inspector-data-value text-gray-700">
                            {props.selectedCourse
                                ? props.selectedCourse.description
                                : ""}
                        </div>
                    </div>
                </div>
                <div className="container">
                    <div className="related-course-wrapper">
                        <div className="inspector-data-label text-gray-500">
                            Prerequisites:
                        </div>
                        <div id="inspector-related-course">
                            {prereqItems(props)}
                        </div>
                    </div>
                    <div className="related-course-wrapper">
                        <div className="inspector-data-label text-gray-500">
                            Concurrent Courses:
                        </div>
                        <div id="inspector-related-course">
                            {concurrentItems(props)}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default CourseInspector;
