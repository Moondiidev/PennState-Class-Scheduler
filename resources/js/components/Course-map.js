import React, { useEffect } from 'react';
import * as d3 from "d3";

function CourseMap(props) {
    let svgWidth = 100;
    let svgHeight = 100;

    const updateMap = () => {
        const mapSvg = d3.select("#course-map");

        svgWidth = mapSvg.node().getBoundingClientRect().width
        svgHeight = mapSvg.node().getBoundingClientRect().height

        console.log("map height, widht: ", svgWidth, svgHeight);
        console.log("course nodes: ", props.courseNodes);
        console.log("course links: ", props.courseLinks);

        const links = props.courseLinks;
        const nodes = props.courseNodes;

        mapSvg.selectAll("g").remove();

        mapSvg.attr("viewBox", [-svgWidth / 2, -svgHeight / 2, svgWidth, svgHeight]);

        const simulation = d3.forceSimulation(nodes)
            .force("link", d3.forceLink(links).id(d => d.id))
            .force("charge", d3.forceManyBody())
            .force("x", d3.forceX())
            .force("y", d3.forceY());

        const link = mapSvg.append("g")
            // .attr("stroke", "#999")
            .attr("stroke-opacity", 0.6)
            .selectAll("line")
            .data(links)
            .join("line")
            .attr("stroke-width", d => Math.sqrt(d.value))
            .attr("stroke", d => (props.selectedCourse && d.id === props.selectedCourse.id) ? d3.rgb(255, 255, 125) : d3.rgb(120, 120, 120))

        const nodeG = mapSvg.append("g")
            .attr("stroke", "#fff")
            .attr("stroke-width", 1.5)
        
        const node = nodeG.selectAll("circle")
            .data(nodes)
            .join("circle")
            .attr("r", 10)
            .attr("fill", color)
            .attr("stroke-width", d => (props.selectedCourse && d.id === props.selectedCourse.id) ? 1 : 0)
            .attr("stroke", d3.rgb(255, 255, 125))
            .call(drag(simulation))
            .on('click', (event, d) => {
                console.log("d clicked: ", d)
                props.selectCourse(d.id)
            })

        node.append("title")
            .text(d => d.abbreviation);

        simulation.on("tick", () => {
            link
                .attr("x1", d => d.source.x)
                .attr("y1", d => d.source.y)
                .attr("x2", d => d.target.x)
                .attr("y2", d => d.target.y);

            node
                .attr("cx", d => d.x)
                .attr("cy", d => d.y);
        })

    }

    const drag = simulation => {

        function dragstarted(event, d) {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            d.fx = d.x;
            d.fy = d.y;
        }

        function dragged(event, d) {
            d.fx = event.x;
            d.fy = event.y;
        }

        function dragended(event, d) {
            if (!event.active) simulation.alphaTarget(0);
            d.fx = null;
            d.fy = null;
        }

        return d3.drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended);
    }

    const color = (d) => {
        const scale = d3.scaleOrdinal(d3.schemeCategory10);
        return d => scale(d.group);
      }

    const initMap = () => {
        const mapSvg = d3.select("#course-map");

        console.log("map.svg: ", mapSvg.node().clientWidth, mapSvg.node().clientWidth)

        svgWidth = mapSvg.node().getBoundingClientRect().width
        svgHeight = mapSvg.node().getBoundingClientRect().height
    }


    useEffect(() => {
        updateMap();
    }, [props.selectedCourse])


    // loads only after initial render
    useEffect(() => {
        initMap();
    }, [])

    return (
        <div id="center-left">
            <svg id="course-map"></svg>
        </div>
    )

}

export default CourseMap;

if (document.getElementById('reactButton')) {
    ReactDOM.render(<CourseMap />, document.getElementById('courseMap'));
}