import React, { useEffect } from 'react';
import * as d3 from "d3";


/**
 * React Component
 *
 * Builds the node/edge structure when a course is selected
 *
 * @author Mark Westerlund
 * @version 1.0
 *
 * @param {Object} props
 * @returns
 */
function CourseMap(props) {
    let width = 568;
    let height = 380;

    let completedColor = d3.rgb(35, 56, 118);
    let incompleteColor = d3.rgb(150, 150, 150);
    let plotColor = d3.rgb(240, 240, 240);
    let plotLabelColor = d3.rgb(80, 80, 80);

    let completedTextColor = d3.rgb(240, 240, 240)
    let incompleteTextColor = d3.rgb(50, 50, 50)

    let textYOffset = 5;
    let circleRadius = 35;

    /**
     * Sets selected course
     * @param {id} id
     */
    const selectCourse = (id) => {
        console.log("course item clicked: ", id);
        props.selectCourse(id);
    };

    /**
     * Initializes the map display on startup
     */
    const initMap = () => {
        console.log("init map", props)

        let mapSvg = d3.select("#course-map")
            .attr("height", height)
            .attr("width", width)

        mapSvg
            .append("text")
            .classed("map-label", 1)
            .attr("y", 10)
            .attr("x", width / 6)
            .attr("text-anchor", "middle")
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text("Prerequisites");

        mapSvg
            .append("text")
            .classed("map-label", 1)
            .attr("y", 10)
            .attr("x", 3 * width / 6)
            .attr("text-anchor", "middle")
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text("Concurrent");

        mapSvg
            .append("text")
            .classed("map-label", 1)
            .attr("y", 10)
            .attr("x", 5 * width / 6)
            .attr("text-anchor", "middle")
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text("Opens Courses");

        mapSvg
            .append("text")
            .attr("id", "map-selected-course-label")
            .classed("map-label", 1)
            .classed("map-course", 1)
            .attr("y", height / 2 + textYOffset)
            .attr("x", 3 * width / 6)
            .attr("text-anchor", "middle")
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text("Select A Course");

    }

    /**
     * Updates map when selected course changes
     * @returns
     */
    const updateMap = () => {

        let centerX = width / 2
        let centerY = height / 2

        let mapSvg = d3.select("#course-map")

        mapSvg.selectAll(".map-course").remove();

        if (!props.selectedCourse) {
            mapSvg
                .append("text")
                .attr("id", "map-selected-course-label")
                .classed("map-label", 1)
                .classed("map-course", 1)
                .attr("y", centerY + textYOffset)
                .attr("x", centerX)
                .attr("text-anchor", "middle")
                .style("fill", plotLabelColor)
                .style("font-size", "11px")
                .text("Select A Course");

            return;
        }

        let selectedCourse = props.selectedCourse;

        // display
        if (selectedCourse.prerequisites.length > 0) {
            let count = selectedCourse.prerequisites.length;

            let prereqs = mapSvg.selectAll(".map-prereq")
                .data(selectedCourse.prerequisites, (d) => {
                    return d.id;
                })
                .enter();

            prereqs
                .append("line")
                .classed("map-line", 1)
                .classed("map-course", 1)
                .classed("map-prereq", 1)
                .attr("x1", width / 6)
                .attr("y1",(d, i) => {
                    return ((i + 1) * height) / (count + 1)
                })
                .attr("x2", centerX)
                .attr("y2", centerY)
                .style("stroke", plotLabelColor)
                .style("stroke-width", 1);

            prereqs
                .append("circle")
                .attr("id", (d) => {
                    return "map-circle-" + d.id;
                })
                .classed("map-circle", 1)
                .classed("map-course", 1)
                .classed("map-prereq", 1)
                .attr("cy", (d, i) => {
                    return ((i + 1) * height) / (count + 1)
                })
                .attr("cx",  width / 6)
                .attr("r", circleRadius)
                .style("fill", (d) => {
                    return (d.isCompleted) ? completedColor : incompleteColor
                })
                .on('click', (event, d) => {
                    selectCourse(d.id);
                })
                .on("mouseover", (event, d) => {
                    d3.select(event.target)
                        .attr("r", circleRadius + 5)
                })
                .on("mouseout", (event, d) => {
                    d3.select(event.target)
                        .attr("r", circleRadius)
                })

            prereqs
                .append("text")
                .classed("map-label", 1)
                .classed("map-course", 1)
                .classed("map-prereq", 1)
                .attr("y", (d, i) => {
                    return ((i + 1) * height) / (count + 1) + textYOffset;
                })
                .attr("x", width / 6)
                .attr("text-anchor", "middle")
                .style("fill", (d) => {
                    return (d.isCompleted) ? completedTextColor : incompleteTextColor
                })
                .style("font-size", "11px")
                .text((d) => {
                    return d.abbreviation;
                })
                .on('click', (event, d) => {
                    selectCourse(d.id);
                })
                .on("mouseover", (event, d) => {
                    d3.select("#map-circle-" + d.id)
                        .attr("r", circleRadius + 5)
                })
                .on("mouseout", (event, d) => {
                    d3.select("#map-circle-" + d.id)
                        .attr("r", circleRadius + 5)
                })
        }

        // display children courses
        if (selectedCourse.childCourses.length > 0) {

            let count = selectedCourse.childCourses.length;

            let children = mapSvg.selectAll(".map-children")
                .data(selectedCourse.childCourses, (d) => {
                    return d.id;
                })
                .enter();

            children
                .append("line")
                .classed("map-line", 1)
                .classed("map-course", 1)
                .classed("map-children", 1)
                .attr("x1", 5 *  width / 6)
                .attr("y1",(d, i) => {
                    return ((i + 1) * height) / (count + 1)
                })
                .attr("x2", centerX)
                .attr("y2", centerY)
                .style("stroke", plotLabelColor)
                .style("stroke-width", 1);

            children
                .append("circle")
                .attr("id", (d) => {
                    return "map-circle-" + d.id;
                })
                .classed("map-circle", 1)
                .classed("map-course", 1)
                .classed("map-children", 1)
                .attr("cy", (d, i) => {
                    return ((i + 1) * height) / (count + 1)
                })
                .attr("cx", 5 *  width / 6)
                .attr("r", circleRadius)
                .style("fill", (d) => {
                    return (d.isCompleted) ? completedColor : incompleteColor
                })
                .on('click', (event, d) => {
                    selectCourse(d.id);
                })
                .on("mouseover", (event, d) => {
                    d3.select(event.target)
                        .attr("r", circleRadius + 5)
                })
                .on("mouseout", (event, d) => {
                    d3.select(event.target)
                        .attr("r", circleRadius)
                });

            children
                .append("text")
                .classed("map-label", 1)
                .classed("map-course", 1)
                .classed("map-children", 1)
                .attr("y", (d, i) => {
                    return ((i + 1) * height) / (count + 1) + textYOffset;
                })
                .attr("x", 5 * width / 6)
                .attr("text-anchor", "middle")
                .style("fill", (d) => {
                    return (d.isCompleted) ? completedTextColor : incompleteTextColor
                })
                .style("font-size", "11px")
                .text((d) => {
                    return d.abbreviation;
                })
                .on('click', (event, d) => {
                    selectCourse(d.id);
                })
                .on("mouseover", (event, d) => {
                    d3.select("#map-circle-" + d.id)
                        .attr("r", circleRadius + 5)
                })
                .on("mouseout", (event, d) => {
                    d3.select("#map-circle-" + d.id)
                        .attr("r", circleRadius + 5)
                });
        }


        if (selectedCourse.concurrents.length > 0 && selectedCourse.concurrents.length <= 1) {
            // I cant think of a better way to do this....
            // just add concurrent 1 and concurrent 2 manually

            let concurrents = selectedCourse.concurrents;

            mapSvg
                .append("line")
                .classed("map-line", 1)
                .classed("map-course", 1)
                .classed("map-children", 1)
                .attr("x1", centerX)
                .attr("y1", (centerY / 2))
                .attr("x2", centerX)
                .attr("y2", centerY)
                .style("stroke", plotLabelColor)
                .style("stroke-width", 1);

            mapSvg
                .append("circle")
                .attr("id",  "map-circle-" + concurrents[0].id )
                .classed("map-circle", 1)
                .classed("map-course", 1)
                .classed("map-children", 1)
                .classed("map-circle", 1)
                .classed("map-course", 1)
                .attr("cy",( centerY / 2))
                .attr("cx", centerX)
                .attr("r", circleRadius)
                .style("fill", (concurrents[0].isCompleted) ? completedColor : incompleteColor)
                .on('click', (event, d) => {
                    selectCourse(concurrents[0].id);
                })
                .on("mouseover", (event, d) => {
                    d3.select(event.target)
                        .attr("r", circleRadius + 5)
                })
                .on("mouseout", (event, d) => {
                    d3.select(event.target)
                        .attr("r", circleRadius)
                });

            mapSvg
                .append("text")
                .attr("id", "map-selected-course-label")
                .classed("map-label", 1)
                .classed("map-course", 1)
                .classed("map-children", 1)
                .attr("y", (centerY / 2 + textYOffset ))
                .attr("x", centerX)
                .attr("text-anchor", "middle")
                .style("fill", (concurrents[0].isCompleted) ? completedTextColor : incompleteTextColor)
                .style("font-size", "11px")
                .text(concurrents[0].abbreviation)
                .on('click', (event, d) => {
                    selectCourse(concurrents[0].id);
                })
                .on("mouseover", (event, d) => {
                    d3.select("#map-circle-" + concurrents[0].id)
                        .attr("r", circleRadius + 5)
                })
                .on("mouseout", (event, d) => {
                    d3.select("#map-circle-" + concurrents[0].id)
                        .attr("r", circleRadius + 5)
                });
        } else if (selectedCourse.concurrents.length > 1) {
            console.error("SELECTED COURSE HAS MORE THAN ONE CONCURRENT COURSE!!!! THIS IS NOT IMPLEMENTED", selectedCourse.concurrents)
        }

        mapSvg
            .append("circle")
            .attr("id", "map-selected-course-label")
            .classed("map-circle", 1)
            .classed("map-course", 1)
            .attr("cy", centerY)
            .attr("cx", centerX)
            .attr("r", circleRadius)
            .style("fill", (selectedCourse.isCompleted) ? completedColor : incompleteColor)

        mapSvg
            .append("text")
            .attr("id", "map-selected-course-label")
            .classed("map-label", 1)
            .classed("map-course", 1)
            .attr("y", centerY + textYOffset)
            .attr("x", centerX)
            .attr("text-anchor", "middle")
            .style("fill", (selectedCourse.isCompleted) ? completedTextColor : incompleteTextColor)
            .style("font-size", "11px")
            .text(selectedCourse.abbreviation);
    }

    /**
     * Update map when selected course changes
     */
    useEffect(() => {
        updateMap();
    }, [props.selectedCourse, props.selectCourse])


    // loads only after initial render
    useEffect(() => {
        initMap();
    }, [])

    return (
        <div className="map-top">
            <svg id="course-map"></svg>
        </div>
    )

}

export default CourseMap;
