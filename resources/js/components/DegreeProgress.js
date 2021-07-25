import React, { useEffect } from "react";
import * as d3 from "d3";

function DegreeProgress(props) {
    let binDimensions = {
        height: 100,
        width: 450,
        padding: {
            top: 5,
            left: 25,
            right: 10,
            bottom: 15,
        },
    };

    let binYScale = null;
    let binXScale = null;

    let binWidth = 0;
    let binPadding = 5;

    let completedColor = d3.rgb(35, 56, 118);
    let incompleteColor = d3.rgb(150, 150, 150);
    let plotColor = d3.rgb(240, 240, 240);
    let plotLabelColor = d3.rgb(80, 80, 80);

    const initCourseBins = () => {
        console.log(props);

        let plotHeight =
            binDimensions.height -
            binDimensions.padding.top -
            binDimensions.padding.bottom;
        let plotWidth =
            binDimensions.width -
            binDimensions.padding.left -
            binDimensions.padding.right;

        binXScale = d3
            .scaleLinear()
            .domain([0, 11])
            .range([
                binDimensions.padding.left + binPadding,
                binDimensions.width - binDimensions.padding.right - binPadding,
            ]);

        binYScale = d3
            .scaleLinear()
            .domain([0, 10])
            .range([
                binDimensions.height - binDimensions.padding.bottom,
                binDimensions.padding.top,
            ]);

        binWidth = binXScale(1) - binXScale(0) - binPadding;

        let barChartSvg = d3
            .select("#degree-bar-chart")
            .attr("height", binDimensions.height)
            .attr("width", binDimensions.width);

        barChartSvg
            .append("rect")
            .attr("x", binDimensions.padding.left)
            .attr("y", binDimensions.padding.top)
            .attr("height", plotHeight)
            .attr("width", plotWidth)
            .style("fill", plotColor);

        // axis labels
        barChartSvg
            .append("text")
            .attr("text-anchor", "middle")
            .attr(
                "transform",
                "translate(" +
                    (binDimensions.padding.left - 5) +
                    "," +
                    (binDimensions.padding.top +
                        (binDimensions.height -
                            binDimensions.padding.top -
                            binDimensions.padding.bottom) /
                            2) +
                    "), rotate(-90)"
            )
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text("Count");

        barChartSvg
            .append("text")
            .attr("text-anchor", "end")
            .attr("x", binDimensions.padding.left)
            .attr("y", binYScale(0) - 2)
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text("0");

        barChartSvg
            .append("text")
            .attr("text-anchor", "end")
            .attr("x", binDimensions.padding.left)
            .attr("y", binYScale(10) + 10)
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text("10");
    };

    const updateCourseBins = () => {
        let bins = props.courseBins.sort((a, b) => {
            return d3.descending(a.total, b.total);
        });

        binXScale = d3
            .scaleLinear()
            .domain([0, 11])
            .range([
                binDimensions.padding.left,
                binDimensions.width - binDimensions.padding.right,
            ]);

        binYScale = d3
            .scaleLinear()
            .domain([0, 10])
            .range([
                binDimensions.height - binDimensions.padding.bottom,
                binDimensions.padding.top,
            ]);

        binWidth = binXScale(1) - binXScale(0) - binPadding;

        let barChartSvg = d3.select("#degree-bar-chart");

        barChartSvg.selectAll(".count-bar").remove();

        var typeLabels = barChartSvg
            .selectAll(".type-label")
            .data(bins, function (d) {
                return d.type;
            })
            .enter();

        typeLabels
            .append("text")
            .attr("id", function (d) {
                return "type-label-" + d.type;
            })
            .classed("type-label", 1)
            .attr("y", binDimensions.height)
            .attr("x", function (d, i) {
                return binXScale(i) + binWidth / 2;
            })
            .attr("text-anchor", "middle")
            .style("fill", plotLabelColor)
            .style("font-size", "11px")
            .text((d) => {
                return d.type;
            });

        var countBar = barChartSvg
            .selectAll(".count-bar")
            .data(bins, function (d) {
                return d.type;
            })
            .enter();

        countBar
            .append("rect")
            .attr("id", function (d) {
                return "count-bar-completed" + d.type;
            })
            .classed("count-bar", 1)
            .attr("y", function (d) {
                return binYScale(d.completed);
            })
            .attr("x", function (d, i) {
                return binXScale(i) + binPadding / 2;
            })
            .attr("height", function (d) {
                return binYScale(0) - binYScale(d.completed);
            })
            .attr("width", binWidth)
            .style("fill", completedColor)
            .append("title")
            .attr("text", (d) => {
                return (
                    d.type +
                    " total: " +
                    d.total +
                    " (complete | incomplete): (" +
                    d.completed +
                    " | " +
                    d.incomplete +
                    ")"
                );
            });

        countBar
            .append("rect")
            .attr("id", function (d) {
                return "count-bar-incomplete" + d.type;
            })
            .classed("count-bar", 1)
            .attr("y", function (d) {
                return binYScale(d.total);
            })
            .attr("x", function (d, i) {
                return binXScale(i) + binPadding / 2;
            })
            .attr("height", function (d) {
                return binYScale(d.completed) - binYScale(d.total);
            })
            .attr("width", binWidth)
            .style("fill", incompleteColor)
            .append("title")
            .attr("text", (d) => {
                return (
                    d.type +
                    " total: " +
                    d.total +
                    " (complete | incomplete): (" +
                    d.completed +
                    " | " +
                    d.incomplete +
                    ")"
                );
            });
    };

    const updateDegreeProgress = () => {
        let svgWidth = 100;
        let svgHeight = 100;

        let degreeCompletion = props.degreeCompletion;

        let percentDone  = Math.round(degreeCompletion.completed / degreeCompletion.total * 100);

        let progressSvg = d3.select("#degree-progress");

        progressSvg.selectAll("g").remove();
        progressSvg.selectAll("text").remove();

        let g = progressSvg
            .append("g")
            .attr(
                "transform",
                "translate(" + svgWidth / 2 + "," + svgHeight / 2 + ")"
            );


        let pie = d3.pie().value(function (d) {
            console.log("pie values: ", d)
            return d.value;
        });

        let arcs = pie(degreeCompletion.pie)

        g.selectAll("whatever")
            .data(arcs)
            .enter()
            .append("path")
            .attr(
                "d",
                d3
                    .arc()
                    .innerRadius(40) // This is the size of the donut hole
                    .outerRadius(50)
            )
            .attr("fill", function (d) {
                return (d.data.key === "completed") ? completedColor : incompleteColor;
            }).append("title")
            .text((d) => {
                return d.data.key + " " +  d.value;
            })

        progressSvg.append("text")
            .attr("x", svgWidth / 2)
            .attr("y", (svgHeight / 2) + 15)
            .attr("text-anchor", "middle")
            .style("fill", plotLabelColor)
            .style("font-size", "35px")
            .text(percentDone + "%")
    };

    const initDegreeProgress = () => {
        console.log(props);

        let svgWidth = 100;
        let svgHeight = 100;

        let progressSvg = d3
            .select("#degree-progress")
            .attr("height", svgHeight)
            .attr("width", svgWidth);
    };

    useEffect(() => {
        initCourseBins();
        initDegreeProgress();
    }, []);

    useEffect(() => {
        console.log("degree progress. ", props);
        updateCourseBins();
        updateDegreeProgress();
    }, [props.courses]);

    return (
        <div className="map-bottom">
            <div className="inspector-data-label text-gray-500">
                Degree Progress
            </div>
            <svg id="degree-bar-chart"></svg>
            <svg id="degree-progress"></svg>
        </div>
    );
}

export default DegreeProgress;
