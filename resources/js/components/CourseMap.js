import React, { useEffect } from 'react';
import * as d3 from "d3";

function CourseMap(props) {
    let svgWidth = 100;
    let svgHeight = 100;

    const initMap = () => {
        
    }

    const updateMap = () => {
       

    }




    useEffect(() => {
        updateMap();
    }, [props.selectedCourse])


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
