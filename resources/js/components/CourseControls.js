import React, { useEffect } from "react";
import filter from "./Model/Filter"

/**
 * React component
 *
 * TODO: Add filter settings
 * - Course type
 * - Completed
 * - Course Level
 *
 * @param {Props} props
 * @returns
 */
function CourseControls(props) {
    console.log("init courseList");
    const COURSE_LEVEL_WIDTH = 200;
    const COURSE_LEVEL_HEIGHT = 100;
    const courseLevelPadding = {
        top: 10,
        left: 5,
        right: 5,
        bottom: 10
    }

    const initCourseLevel = () => {

    }



    const setCompletedFilter = () => {
        console.log("button clicked", props.filterSettings);
        props.setFilter(filter.COMPLETED, !props.filterSettings.completedFilter)
    }

    useEffect(() => {
        initCourseLevel();
    }, [])

    return (
        <div className="right">
            <div className="controls-section-label">CONTROLS</div>
            <div className="controls-setting-row">
                <div className="control-setting-label text-gray-500">
                    Show Completed Courses
                </div>
                <button
                    className={"bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-2 " + 
                        (props.filterSettings.completedFilter
                            ? "bg-gray-500"
                            : "bg-blue-900 hover:bg-blue-700")
                    }
                    onClick={setCompletedFilter}
                >
                    {props.filterSettings.completedFilter ? "Hide" : "Show"}
                </button>
            </div>
            <div className="controls-setting-row">
                <div className="control-setting-label text-gray-500">
                    Show Completed Courses
                </div>

                <svg id="control-course-level"></svg>
            </div>
        </div>
    );
}

export default CourseControls;
