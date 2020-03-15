/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Line charts
 *
 *  Demo JS code for echarts_lines.html page
 *
 * ---------------------------------------------------------------------------- */

// Setup module
// ------------------------------

var EchartsLines = (function() {
  //
  // Setup module components
  //

  // Line charts
  var _lineChartExamples = function() {
    if (typeof echarts == "undefined") {
      console.warn("Warning - echarts.min.js is not loaded.");
      return;
    }

    // Define elements
    var line_basic_element = document.getElementById("line_basic");
    var line_basic_element1 = document.getElementById("line_basic1");
    var line_basic_element2 = document.getElementById("line_basic2");
    var line_stacked_element = document.getElementById("line_stacked");

    //
    // Charts configuration
    //

    // Basic line chart
    if (line_basic_element2) {
      // Initialize chart
      var line_basic2 = echarts.init(line_basic_element2);

      //
      // Chart config
      //

      // Options
      line_basic2.setOption({
        // Define colors
        color: ["#2196F3", "#66BB6A", "#EF5350"],

        // Global text styles
        textStyle: {
          fontFamily: "Roboto, Arial, Verdana, sans-serif",
          fontSize: 13
        },

        // Chart animation duration
        animationDuration: 750,

        // Setup grid
        grid: {
          left: 0,
          right: 40,
          top: 35,
          bottom: 0,
          containLabel: true
        },

        // Add legend
        legend: {
          data: ["Total Agent", "Agent OK", "Agent NOK"],
          itemHeight: 8,
          itemGap: 20
        },

        // Add tooltip
        tooltip: {
          trigger: "axis",
          backgroundColor: "rgba(0,0,0,0.75)",
          padding: [10, 15],
          textStyle: {
            fontSize: 13,
            fontFamily: "Roboto, sans-serif"
          }
        },

        // Horizontal axis
        xAxis: [
          {
            type: "category",
            boundaryGap: false,
            data: [
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "10",
              "11",
              "12",
              "13",
              "14",
              "15",
              "16",
              "17",
              "18",
              "19",
              "20",
              "21",
              "22",
              "23",
              "24",
              "25",
              "26",
              "27",
              "28",
              "29",
              "30",
              "31"
            ],
            axisLabel: {
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            }
          }
        ],

        // Vertical axis
        yAxis: [
          {
            type: "value",
            axisLabel: {
              formatter: "{value}",
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            },
            splitArea: {
              show: true,
              areaStyle: {
                color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"]
              }
            }
          }
        ],

        // Add series
        series: [
          {
            name: "Total Agent",
            type: "line",
            data: [11, 11, 15, 13, 12, 13, 10],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Agent OK",
            type: "line",
            data: [1, 22, 22, 25, 32, 22, 20],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Agent NOK",
            type: "line",
            data: [31, 24, 24, 35, 33, 12, 30],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          }
        ]
      });
    }

    // Basic line chart
    if (line_basic_element1) {
      // Initialize chart
      var line_basic1 = echarts.init(line_basic_element1);

      //
      // Chart config
      //

      // Options
      line_basic1.setOption({
        // Define colors
        color: ["#2196F3", "#66BB6A", "#EF5350"],

        // Global text styles
        textStyle: {
          fontFamily: "Roboto, Arial, Verdana, sans-serif",
          fontSize: 13
        },

        // Chart animation duration
        animationDuration: 750,

        // Setup grid
        grid: {
          left: 0,
          right: 40,
          top: 35,
          bottom: 0,
          containLabel: true
        },

        // Add legend
        legend: {
          data: ["Total Responden", "Puas", "Tiket Puas"],
          itemHeight: 8,
          itemGap: 20
        },

        // Add tooltip
        tooltip: {
          trigger: "axis",
          backgroundColor: "rgba(0,0,0,0.75)",
          padding: [10, 15],
          textStyle: {
            fontSize: 13,
            fontFamily: "Roboto, sans-serif"
          }
        },

        // Horizontal axis
        xAxis: [
          {
            type: "category",
            boundaryGap: false,
            data: [
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "10",
              "11",
              "12",
              "13",
              "14",
              "15",
              "16",
              "17",
              "18",
              "19",
              "20",
              "21",
              "22",
              "23",
              "24",
              "25",
              "26",
              "27",
              "28",
              "29",
              "30",
              "31"
            ],
            axisLabel: {
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            }
          }
        ],

        // Vertical axis
        yAxis: [
          {
            type: "value",
            axisLabel: {
              formatter: "{value}",
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            },
            splitArea: {
              show: true,
              areaStyle: {
                color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"]
              }
            }
          }
        ],

        // Add series
        series: [
          {
            name: "Total Responden",
            type: "line",
            data: [11, 11, 15, 13, 12, 13, 10],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Puas",
            type: "line",
            data: [1, 22, 22, 25, 32, 22, 20],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Tiket Puas",
            type: "line",
            data: [31, 24, 24, 35, 33, 12, 30],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          }
        ]
      });
    }

    // Basic line chart
    if (line_basic_element) {
      // Initialize chart
      var line_basic = echarts.init(line_basic_element);

      //
      // Chart config
      //

      // Options
      line_basic.setOption({
        // Define colors
        color: ["#EF5350", "#66BB6A", "#2196F3"],

        // Global text styles
        textStyle: {
          fontFamily: "Roboto, Arial, Verdana, sans-serif",
          fontSize: 13
        },

        // Chart animation duration
        animationDuration: 750,

        // Setup grid
        grid: {
          left: 0,
          right: 40,
          top: 35,
          bottom: 0,
          containLabel: true
        },

        // Add legend
        legend: {
          data: ["Total Incident Logic", "Closed by Frontliner", "Tiket Logic"],
          itemHeight: 8,
          itemGap: 20
        },

        // Add tooltip
        tooltip: {
          trigger: "axis",
          backgroundColor: "rgba(0,0,0,0.75)",
          padding: [10, 15],
          textStyle: {
            fontSize: 13,
            fontFamily: "Roboto, sans-serif"
          }
        },

        // Horizontal axis
        xAxis: [
          {
            type: "category",
            boundaryGap: false,
            data: [
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "10",
              "11",
              "12",
              "13",
              "14",
              "15",
              "16",
              "17",
              "18",
              "19",
              "20",
              "21",
              "22",
              "23",
              "24",
              "25",
              "26",
              "27",
              "28",
              "29",
              "30",
              "31"
            ],
            axisLabel: {
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            }
          }
        ],

        // Vertical axis
        yAxis: [
          {
            type: "value",
            axisLabel: {
              formatter: "{value}",
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            },
            splitArea: {
              show: true,
              areaStyle: {
                color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"]
              }
            }
          }
        ],

        // Add series
        series: [
          {
            name: "Total Incident Logic",
            type: "line",
            data: [11, 11, 15, 13, 12, 13, 10],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Closed by Frontliner",
            type: "line",
            data: [1, 22, 22, 25, 32, 22, 20],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Tiket Logic",
            type: "line",
            data: [31, 24, 24, 35, 33, 12, 30],
            smooth: true,
            symbolSize: 7,
            markLine: {
              data: [
                {
                  type: "average",
                  name: "Average"
                }
              ]
            },
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          }
        ]
      });
    }

    // Stacked lines chart
    if (line_stacked_element) {
      // Initialize chart
      var line_stacked = echarts.init(line_stacked_element);

      //
      // Chart config
      //

      // Options
      line_stacked.setOption({
        // Global text styles
        textStyle: {
          fontFamily: "Roboto, Arial, Verdana, sans-serif",
          fontSize: 13
        },

        // Chart animation duration
        animationDuration: 750,

        // Setup grid
        grid: {
          left: 0,
          right: 20,
          top: 35,
          bottom: 0,
          containLabel: true
        },

        // Add legend
        legend: {
          data: [
            "Total Transaksi",
            "Transaksi Add On",
            "Transaksi PSB",
            "CWC REGIS"
          ],
          itemHeight: 8,
          itemGap: 20
        },

        // Add tooltip
        tooltip: {
          trigger: "axis",
          backgroundColor: "rgba(0,0,0,0.75)",
          padding: [10, 15],
          textStyle: {
            fontSize: 13,
            fontFamily: "Roboto, sans-serif"
          }
        },

        // Horizontal axis
        xAxis: [
          {
            type: "category",
            boundaryGap: false,
            data: [
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "10",
              "11",
              "12",
              "13",
              "14",
              "15",
              "16",
              "17",
              "18",
              "19",
              "20",
              "21",
              "22",
              "23",
              "24",
              "25",
              "26",
              "27",
              "28",
              "29",
              "30",
              "31"
            ],
            axisLabel: {
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            }
          }
        ],

        // Vertical axis
        yAxis: [
          {
            type: "value",
            axisLabel: {
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
              }
            },
            splitLine: {
              lineStyle: {
                color: ["#eee"]
              }
            },
            splitArea: {
              show: true,
              areaStyle: {
                color: ["rgba(250,250,250,0.1)", "rgba(0,0,0,0.01)"]
              }
            }
          }
        ],

        // Add series
        series: [
          {
            name: "Total Transaksi",
            type: "line",
            stack: "Total",
            smooth: true,
            symbolSize: 7,
            data: [120, 132, 101, 134, 90, 230, 210],
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Transaksi Add On",
            type: "line",
            stack: "Total",
            smooth: true,
            symbolSize: 7,
            data: [220, 182, 191, 234, 290, 330, 310],
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "Transaksi PSB",
            type: "line",
            stack: "Total",
            smooth: true,
            symbolSize: 7,
            data: [150, 232, 201, 154, 190, 330, 410],
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          },
          {
            name: "CWC REGIS",
            type: "line",
            stack: "Total",
            smooth: true,
            symbolSize: 7,
            data: [320, 332, 301, 334, 390, 330, 320],
            itemStyle: {
              normal: {
                borderWidth: 2
              }
            }
          }
        ]
      });
    }

    //
    // Resize charts
    //

    // Resize function
    var triggerChartResize = function() {
      line_basic_element && line_basic.resize();
      line_basic_element1 && line_basic1.resize();
      line_basic_element2 && line_basic2.resize();
      line_stacked_element && line_stacked.resize();
    };

    // On sidebar width change
    $(document).on("click", ".sidebar-control, .navbar-toggler", function() {
      setTimeout(function() {
        triggerChartResize();
      }, 0);
    });

    // On window resize
    var resizeCharts;
    window.onresize = function() {
      clearTimeout(resizeCharts);
      resizeCharts = setTimeout(function() {
        triggerChartResize();
      }, 200);
    };

    // Resize charts when hidden element becomes visible
    $('.nav-link[data-toggle="tab"]').on("shown.bs.tab", function(e) {
      triggerChartResize();
    });
  };

  //
  // Return objects assigned to module
  //

  return {
    init: function() {
      _lineChartExamples();
    }
  };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function() {
  EchartsLines.init();
});
