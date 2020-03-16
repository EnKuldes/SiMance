/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Column and Waterfall charts
 *
 *  Demo JS code for echarts_columns_waterfalls.html page
 *
 * ---------------------------------------------------------------------------- */

// Setup module
// ------------------------------

var EchartsColumnsWaterfalls = (function() {
  //
  // Setup module components
  //

  // Column and waterfall charts
  var _columnsWaterfallsExamples = function() {
    if (typeof echarts == "undefined") {
      console.warn("Warning - echarts.min.js is not loaded.");
      return;
    }

    // Define elements
    var columns_basic_element = document.getElementById("columns_basic");
    var columns_basic_element1 = document.getElementById("columns_basic1");

    //
    // Charts configuration
    //

    // Basic columns chart
    if (columns_basic_element) {
      // Initialize chart
      var columns_basic = echarts.init(columns_basic_element);

      //
      // Chart config
      //

      // Options
      columns_basic.setOption({
        // Define colors
        color: ["#2ec7c9", "#b6a2de", "#5ab1ef", "#ffb980", "#d87a80"],

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
          data: ["COF", "Call W 20 Sec"],
          itemHeight: 8,
          itemGap: 20,
          textStyle: {
            padding: [0, 5]
          }
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
              show: true,
              lineStyle: {
                color: "#eee",
                type: "dashed"
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
            name: "COF",
            type: "bar",
            data: [
              2.0,
              4.9,
              7.0,
              23.2,
              25.6,
              76.7,
              15.6,
              12.2,
              32.6,
              20.0,
              6.4,
              3.3
            ],
            itemStyle: {
              normal: {
                label: {
                  show: true,
                  position: "top",
                  textStyle: {
                    fontWeight: 500
                  }
                }
              }
            },
            markLine: {
              data: [{ type: "average", name: "Average" }]
            }
          },
          {
            name: "Call W 20 Sec",
            type: "bar",
            data: [
              2.6,
              5.9,
              9.0,
              26.4,
              58.7,
              70.7,
              17.6,
              12.2,
              48.7,
              18.8,
              6.0,
              2.3
            ],
            itemStyle: {
              normal: {
                label: {
                  show: true,
                  position: "top",
                  textStyle: {
                    fontWeight: 500
                  }
                }
              }
            },
            markLine: {
              data: [{ type: "average", name: "Average" }]
            }
          }
        ]
      });
    }

    // Basic columns chart
    if (columns_basic_element1) {
      // Initialize chart
      var columns_basic1 = echarts.init(columns_basic_element1);

      //
      // Chart config
      //

      // Options
      columns_basic1.setOption({
        // Define colors
        color: ["#2ec7c9", "#b6a2de", "#5ab1ef", "#ffb980", "#d87a80"],

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
          data: ["Bobot"],
          itemHeight: 8,
          itemGap: 20,
          textStyle: {
            padding: [0, 5]
          }
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
            data: ["Last Month", "This Month"],
            axisLabel: {
              color: "#333"
            },
            axisLine: {
              lineStyle: {
                color: "#999"
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
            name: "Last Month",
            type: "bar",
            data: [
              77
            ],
            itemStyle: {
              normal: {
                label: {
                  show: true,
                  position: "top",
                  textStyle: {
                    fontWeight: 500
                  }
                }
              }
            }
          },
          {
            name: "This Month",
            type: "bar",
            data: [
              80
            ],
            itemStyle: {
              normal: {
                label: {
                  show: true,
                  position: "top",
                  textStyle: {
                    fontWeight: 500
                  }
                }
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
      columns_basic_element && columns_basic.resize();
      columns_basic_element1 && columns_basic1.resize();
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
      _columnsWaterfallsExamples();
    }
  };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function() {
  EchartsColumnsWaterfalls.init();
});
