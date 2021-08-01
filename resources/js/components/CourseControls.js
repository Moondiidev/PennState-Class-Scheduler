import React, { useEffect } from "react";
import filter from "./Model/Filter";
import * as d3 from "d3";

/**
 * React component
 *
 * Controls filter filters for degree courses
 * 
 * TODO: Add filter settings
 * - Course type
 *
 * @param {Props} props
 * @returns
 */
function CourseControls(props) {
    const COURSE_LEVEL_WIDTH = 200;
    const COURSE_LEVEL_HEIGHT = 70;
    const courseLevelPadding = {
        top: 10,
        left: 10,
        right: 10,
        bottom: 10,
    };

    const plotLabelColor = d3.rgb(80, 80, 80);
    const plotAxisColor = d3.rgb(80, 80, 80);
    const completedColor = d3.rgb(35, 56, 118);

    const levels = [0, 1, 2, 3, 4];

    const levelScale = d3
        .scaleLinear()
        .domain([0, 4])
        .range([
            courseLevelPadding.left,
            COURSE_LEVEL_WIDTH - courseLevelPadding.right,
        ]);

    const centerY = COURSE_LEVEL_HEIGHT / 2;

    const tickHeight = 10;
    const fontHeight = 11;
    const markerRadius = 5;
    const clickZoneWidth = levelScale(1) - levelScale(0);
    const clickZoneHeight = (tickHeight + fontHeight) * 2;

    let filterLoaded = false;

    /**
     * initialize the course level slider
     */
    const initCourseLevel = () => {
        let courseLevelSvg = d3
            .select("#control-course-level")
            .attr("height", COURSE_LEVEL_HEIGHT)
            .attr("width", COURSE_LEVEL_WIDTH);

        courseLevelSvg
            .append("line")
            .attr("x1", levelScale(0))
            .attr("x2", levelScale(4))
            .attr("y1", centerY)
            .attr("y2", centerY)
            .style("stroke-width", 1)
            .style("stroke", plotAxisColor);

        let ticks = courseLevelSvg
            .selectAll(".course-ticks")
            .data(levels)
            .enter();

        ticks
            .append("line")
            .classed("course-ticks", 1)
            .attr("x1", (d) => {
                return levelScale(d);
            })
            .attr("x2", (d) => {
                return levelScale(d);
            })
            .attr("y1", centerY - tickHeight / 2)
            .attr("y2", centerY + tickHeight / 2)
            .style("stroke-width", 1)
            .style("stroke", plotAxisColor);

        ticks
            .append("text")
            .classed("course-ticks", 1)
            .attr("x", (d) => {
                return levelScale(d);
            })
            .attr("y", centerY + tickHeight + fontHeight)
            .style("fill", plotLabelColor)
            .style("font-size", fontHeight + "px")
            .attr("text-anchor", "middle")
            .text((d) => {
                return d === 0 ? "All" : d * 100;
            });

        ticks
            .append("rect")
            .classed("course-ticks", 1)
            .classed("course-rect", 1)
            .attr("y", centerY - clickZoneHeight / 2)
            .attr("x", (d) => {
                return levelScale(d) - clickZoneWidth / 2;
            })
            .attr("height", clickZoneHeight)
            .attr("width", clickZoneWidth)
            .style("fill", "transparent")
            // .style("stroke-width", 1)
            // .style("stroke", plotAxisColor)
            .on("click", (event, d) => {
                console.log("SET FILTER CLICK: ", d);
                setSetLevelFilter(d * 100);
            });
    };

    /**
     * increase the radius of course marker when starting drag
     */
    function dragstarted() {
        d3.select(this).attr("r", markerRadius + 2);
    }

    /**
     * Prevent slider from going out of bounds
     * @param {Event} event 
     */
    function dragged(event) {
        let x = event.x;

        if (x < levelScale(0)) {
            x = courseLevelPadding.left;
        } else if (x > levelScale(4)) {
            x = levelScale(4);
        }

        d3.select(this).raise().attr("cx", x);
    }

    /**
     * Set the filter after drag release
     * @param {Event} event 
     */
    function dragended(event) {
        console.log("DRAGGEND FILTER LOADED: ", filterLoaded);
        if (filterLoaded) {
            let x = event.x;
            console.log("draggend x: ", x);

            if (x > levelScale(4)) {
                x = levelScale(4);
            } else if (x < levelScale(0)) {
                x = levelScale(0);
            }

            let level = Math.round(levelScale.invert(x));

            console.log("draggend: ", level);

            d3.select(this)
                .attr("r", markerRadius)
                .attr("cx", levelScale(level));

            setSetLevelFilter(level * 100);
        } else {
            console.log("LOAD FILTER DRAG");
        }
    }

    /**
     * Updates the course level marker
     */
    const updateCourseLevel = () => {
        let courseLevelSvg = d3.select("#control-course-level");

        console.log("filter loaded", filterLoaded);

        courseLevelSvg.selectAll(".course-level-marker").remove();
        // courseLevelSvg.selectAll(".course-rects").remove();

        courseLevelSvg
            .append("circle")
            .classed("course-level-marker", 1)
            .attr("cy", centerY)
            .attr("cx", levelScale(props.filterSettings.courseLevel / 100))
            .attr("r", markerRadius)
            .style("fill", completedColor)
            .call(
                d3
                    .drag()
                    .on("start", dragstarted)
                    .on("drag", dragged)
                    .on("end", dragended)
            );

        filterLoaded = true;

        // This resets the on click handler to the latest filter settings.
        // this needs to happen because the way react hooks makes deep copies of functions 
        // stored in props
        let ticks = courseLevelSvg.selectAll(".course-rect").data(levels);

        ticks.on("click", (event, d) => {
            console.log("SET FILTER CLICK: ", d);
            setSetLevelFilter(d * 100);
        });
    };

    /**
     * Set completed filter pass through
     */
    const setCompletedFilter = () => {
        console.log("button clicked", props.filterSettings);
        props.setFilter(
            filter.COMPLETED,
            !props.filterSettings.completedFilter
        );
    };

    /**
     * Sets level filter pass through
     * @param {Number} level 
     */
    const setSetLevelFilter = (level) => {
        console.log("filterSetting clicked", props.filterSettings);
        props.setFilter(filter.COURSE_LEVEL, level);
    };

    // React hook to initialize slider axis
    useEffect(() => {
        initCourseLevel();
    }, []);

    /**
     * React hook to update slider when filter changes
     */
    useEffect(() => {
        filterLoaded = false;
        updateCourseLevel();
    }, [props.filterSettings]);

    return (
        <div className="right">
            <div className="controls-section-label">CONTROLS</div>
            <div className="controls-setting-row">
                <div className="control-setting-label text-gray-500">
                    Show Completed Courses
                </div>
                <button
                    className={
                        "bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-2 " +
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
                    Show Courses Above Level:
                </div>

                <svg id="control-course-level"></svg>
            </div>
        </div>
    );
}

export default CourseControls;