@extends('layouts/app')

@section('title', 'CC 147')

@section('liblary')
<script src="assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
@endsection

@section('extra-liblary')
{{--
<script src="assets/js/demo_pages/charts/echarts/columns_waterfalls.js"></script>
<script src="assets/js/demo_pages/charts/echarts/lines.js"></script>
--}}
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
<script src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
@endsection

@section('script')
{{-- Javascript --}}
<script type="text/javascript">
  function numbersonly(e){
    var unicode=e.charCode? e.charCode : e.keyCode
    if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
        if (unicode<48||unicode>57) //if not a number
            return false //disable key press
    }
}

  $('.equipCatValidation').on('keyup keydown', function(e){
    console.log($(this).val() > 100)
        if ($(this).val() > 100
            && e.keyCode !== 46
            && e.keyCode !== 8
           ) {
           e.preventDefault();
           $(this).val(100);
        }
    });

  // Func Chaining
  function chain1() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/list-parameter',
         //data: {},
         success: function(data){

          var ahtml = '<option></option>';
          for (var i = 0; i < data.length; i++) {
            ahtml+="<option value='"+data[i]['id']+"'>"+data[i]['parameter_desc']+"</option>"
          }
          $('#select_parameter').html(ahtml);
        },
        error : function(data) {

          console.log("error chain1");
        }
      }).done(function(){

      });
    }
    function chain2(id) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
       type:"post",
       url:'/list-formulasi',
       data: {'id_parameter':id},
       success: function(data){
        var ahtml = '<option></option>';
        for (var i = 0; i < data.length; i++) {
          ahtml+="<option value='"+data[i]['id']+"'>"+data[i]['formulasi_desc']+"</option>"
        }
        $('#select_formulasi').html(ahtml);

      },
      error : function(data) {

        console.log("error chain2");

      }
    }).done(function(){

    });
  }
  function chain3() {
      var tempYear = 0;
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
       type:"post",
       url:'/list-date',
       //data: {'id_parameter':id},
       success: function(data){
        var ahtml = ''//'<option></option>';
        for (var i = 0; i < data.length; i++) {
          ahtml+="<option value='"+data[i]['year']+"'>"+data[i]['year']+"</option>"
          if (i == 0) { tempYear = data[i]['year']; }
        }
        $('#list_year').html(ahtml);
      },
      error : function(data) {

        console.log("error chain2");

      }
    }).done(function(){
      $('#list_year').val(tempYear).trigger('change');

    });
  }
  function chain4(id) {
      var tempMonth = 0;
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
       type:"post",
       url:'/list-date',
       data: {'select_year':id},
       success: function(data){
        var ahtml = ''//'<option></option>';
        for (var i = 0; i < data.length; i++) {
          ahtml+="<option value='"+data[i]['month']+"'>"+data[i]['month']+"</option>"
          if (i == 0) { tempMonth = data[i]['month']; }
        }
        $('#list_month').html(ahtml);

      },
      error : function(data) {

        console.log("error chain2");

      }
    }).done(function(){
      $('#list_month').val(tempMonth).trigger('change');

    });
  }
  // Func-Func
  function tab_for_parameter(id_parameter) {
    get_current_kpi(id_parameter);
    $('#form-target-bobot input[name=value_parameter]').val(id_parameter);
  }
  function get_current_kpi(id_parameter) {
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
       type:"post",
       url:'/get-current-kpi',
       data: {'id_parameter':id_parameter},
       success: function(data){
        var spanTitles = ['cur_target', 'cur_bobot', 'param_name'];
        var valueTitles = ['cur_target', 'cur_bobot', 'parameter_desc'];
        for (var i = 0; i < spanTitles.length; i++) {
          $("#" + spanTitles[i]).html(data[0][valueTitles[i]]);
        }
      },
      error : function(data) {

        console.log("Error");

      }
    }).done(function(){

    });
  }

  function get_realisasi_monthly() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-realisasi-monthly',
         //data: {year: year_value, month: month_value, id_parameter: parameter_value},
         success: function(data){
          var labelTitles = ['sl_val', 'fcr_val', 'rs_val', 'ces_val', 'ql_val']
          console.log(data)
          for (var i = 0; i < labelTitles.length; i++) {
            if (data[i]['realisasi'] != null) {realisasi = data[i]['realisasi']}
            else{realisasi = 0}
            $('#'+labelTitles[i]).html( realisasi+"%" )
          }
        },
        error: function(jqXhr, json, errorThrown){// this are default for ajax errors
          var errors = jqXhr.responseJSON;
          var errorsHtml = '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error ' + jqXhr.status + ': ' + errorThrown + '</div>';
          notificationScript("error", "Error " + jqXhr.status, errorThrown);
          $.each(errors['errors'], function (index, value) {
            errorsHtml += '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + value + '</div>';
            notificationScript("error", "Error Field", value);
          });
        }
      }).done(function(){

      });
    }

  // Form On Submit
  $('#form-insert-daily').on('submit', function(e){
    e.preventDefault();
    $('#saveBtn').button('loading');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/daily/save',
     data: $( this ).serialize(),
     success: function(data){
      $('#saveBtn').button('reset');
      notificationScript("success", "Success", "Successfully submit form.");
      refresh_charts();
      $('#insert-new-data').modal('hide');
    },
          error: function(jqXhr, json, errorThrown){// this are default for ajax errors
            $('#saveBtn').button('reset');
            var errors = jqXhr.responseJSON;
            var errorsHtml = '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error ' + jqXhr.status + ': ' + errorThrown + '</div>';
            notificationScript("error", "Error " + jqXhr.status, errorThrown);
            $.each(errors['errors'], function (index, value) {
              errorsHtml += '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + value + '</div>';
              notificationScript("error", "Error Field", value);
            });

          }
        }).done(function(){

        });

      });
    // Document Ready
    $(document).ready(function() {
      chain1();
      chain3();
      reset_input();
      init_chart_element();
      get_realisasi_monthly()
      $('#input_date').pickadate({format: 'yyyy-mm-dd'});
    });
  // On Change Events
  $("#select_parameter").change(function() {
    var id = $(this).val();
    if (id != "" && id != null)
    {
      chain2(id);
    }
  });
  $("#list_year").change(function() {
    var id = $(this).val();
    if (id != "" && id != null)
    {
      chain4(id);
    }
  });
  $("#list_month").change(function() {
    var id = $(this).val();
    if (id != "" && id != null)
    {
      init_chart_value( $("#list_year").val(), $(this).val() );
    }
  });
  // Button On Click
  function reset_input() {
    $("#form-insert-daily").trigger("reset");
    //$("select").val('').trigger('change');
    $("#select_formulasi").html('');
  }
  // on Close Modal Event
  $('#insert-new-data').on('hidden.bs.modal', function () {
      //var element = $(this).find('form input[name = "id"]')
      //element.remove()
      reset_input();
    });
</script>
<script type="text/javascript">
  // Charts
  var columns_basic = echarts.init( document.getElementById('columns_basic') ); // service level
  var line_basic = echarts.init( document.getElementById("line_basic") ); // fcr
  var line_basic1 = echarts.init( document.getElementById("line_basic1") ); // ces
  var line_basic2 = echarts.init( document.getElementById("line_basic2") ); // quality layanan
  var line_stacked = echarts.init( document.getElementById("line_stacked") ); // rasio sales

  // Options
  // Yang perlu diisi Legend.data, xAxis.data, Series
  var columns_basic_options = {
    // Define colors
    color: ['#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80'],
    // Global text styles
    textStyle: {
      fontFamily: 'Roboto, Arial, Verdana, sans-serif',
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
      //data: ['COF', 'Call W 20 Sec'],
      itemHeight: 8,
      itemGap: 20,
      textStyle: {
        padding: [0, 5]
      }
    },
    // Add tooltip
    tooltip: {
      trigger: 'axis',
      backgroundColor: 'rgba(0,0,0,0.75)',
      padding: [10, 15],
      textStyle: {
        fontSize: 13,
        fontFamily: 'Roboto, sans-serif'
      }
    },
    // Horizontal axis
    xAxis: [{
      type: 'category',
      //data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
      axisLabel: {
        color: '#333'
      },
      axisLine: {
        lineStyle: {
          color: '#999'
        }
      },
      splitLine: {
        show: true,
        lineStyle: {
          color: '#eee',
          type: 'dashed'
        }
      }
    }],
    // Vertical axis
    yAxis: [{
      type: 'value',
      axisLabel: {
        color: '#333'
      },
      axisLine: {
        lineStyle: {
          color: '#999'
        }
      },
      splitLine: {
        lineStyle: {
          color: ['#eee']
        }
      },
      splitArea: {
        show: true,
        areaStyle: {
          color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
        }
      }
    }],
    // Add series
    /*series: [
    {
      name: 'COF',
      type: 'bar',
      data: [2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 15.6, 12.2, 32.6, 20.0, 6.4, 3.3],
      itemStyle: {
        normal: {
          label: {
            show: true,
            position: 'top',
            textStyle: {
              fontWeight: 500
            }
          }
        }
      },
      markLine: {
        data: [{type: 'average', name: 'Average'}]
      }
    },
    {
      name: 'Call W 20 Sec',
      type: 'bar',
      data: [2.6, 5.9, 9.0, 26.4, 58.7, 70.7, 17.6, 12.2, 48.7, 18.8, 6.0, 2.3],
      itemStyle: {
        normal: {
          label: {
            show: true,
            position: 'top',
            textStyle: {
              fontWeight: 500
            }
          }
        }
      },
      markLine: {
        data: [{type: 'average', name: 'Average'}]
      }
    }
    ]*/
    };
  var line_basic_options = {
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
          //data: ["Total Incident Logic", "Closed by Frontliner", "Tiket Logic"],
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
            //data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
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
        /*series: [
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
        ]*/
      };
  var line_basic1_options = {
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
          //data: ["Total Responden", "Puas", "Tiket Puas"],
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
            //data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
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
        /*series: [
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
        ]*/
      };
  var line_basic2_options = {
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
          //data: ["Total Agent", "Agent OK", "Agent NOK"],
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
            //data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
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
        /*series: [
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
        ]*/
      };
  var line_stacked_options = {
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
          //data: ["Total Transaksi", "Transaksi Add On", "Transaksi PSB", "CWC REGIS"],
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
            //data: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
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
        /*series: [
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
        ]*/
      };
  // Func untuk Transform data dengan mencari key yang unique lalu
  function trans_val(data, key) {
    var resArr = [];
    data.filter(function(item){
      var i = resArr.findIndex(x => (x[key] == item[key]));
      if(i <= -1){
            resArr.push(item);
      }
      return null;
    });
    //console.log(resArr)
    var retArr = []
    for (var i = 0; i < resArr.length; i++) {
      retArr.push(resArr[i][key])
    }
    return retArr;
  }
  function list_legend(data, value_parameter) {
    window["data_"+value_parameter] =[];
    for (var i = 0; i < data.length; i++) {
      if (data[i]['parameter_desc'] == value_parameter) {
        window["data_"+value_parameter].push(data[i]['value_desc'])
      }
    }
    var uniqueValueDesc = []
    $.each(window["data_"+value_parameter], function(i, el){
      if($.inArray(el, uniqueValueDesc) === -1) uniqueValueDesc.push(el);
    });
    //console.log( uniqueValueDesc )
    return uniqueValueDesc;
  }
  function list_data_series(data, value_parameter, value_desc, type_chart) {
    var tempArr = [];
    for (var j = 0; j < value_desc.length; j++) {
      window["data_"+value_parameter+value_desc[j]] =[];
      for (var i = 0; i < data.length; i++) {
        if (data[i]['parameter_desc'] == value_parameter && data[i]['value_desc'] == value_desc[j] ) {
          window["data_"+value_parameter+value_desc[j]].push(data[i]['value_item'])
        }
      }
      //console.log( window["data_"+value_parameter+value_desc[j]] )
      tempArr.push({
        name: value_desc[j],
        type: type_chart,
        data: window["data_"+value_parameter+value_desc[j]]
      })
    }
    //console.log(tempArr)
    return tempArr;
  }

  // mencari value
  function init_chart_value(year_value, month_value){
    var list_option_charts = [columns_basic_options, line_basic_options, line_stacked_options, line_basic1_options, line_basic2_options]
    var list_type_charts = ['bar', 'line', 'line', 'line', 'line']
    var charts = [columns_basic, line_basic, line_stacked, line_basic1, line_basic2];
    for (var i = 0; i < list_option_charts.length; i++) {
      get_monthly_data( $("#list_year").val(), $('#list_month').val(), i+1, charts[i], list_option_charts[i], list_type_charts[i])
    }
    //get_monthly_data( $("#list_year").val(), $('#list_month').val(), 1, charts[0], list_option_charts[0], list_type_charts[0])
  }
  
  // Get Data Monthly
  function get_monthly_data(year_value, month_value, parameter_value, chart_element, chart_option, chart_type) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-monthly-data',
         data: {year: year_value, month: month_value, id_parameter: parameter_value},
         success: function(data){
          var list_day = trans_val(data, 'day');
          chart_option.xAxis[0].data = list_day;
          var list_parameter = trans_val(data, 'parameter_desc');
          var tempVal =  list_parameter;
          var tempList = list_legend(data, tempVal);
          chart_option.legend.data = tempList;
          chart_option.series = list_data_series(data, tempVal, tempList, chart_type);
          chart_element.setOption(chart_option, true);
          /*columns_basic_options.xAxis[0].data = list_day;
          line_basic_options.xAxis[0].data = list_day;
          line_basic1_options.xAxis[0].data = list_day;
          line_basic2_options.xAxis[0].data = list_day;
          line_stacked_options.xAxis[0].data = list_day;

          var list_parameter = trans_val(data, 'parameter_desc');

          var tempVal =  list_parameter[0];
          var tempList = list_legend(data, tempVal);
          columns_basic_options.legend.data = tempList;
          columns_basic_options.series = list_data_series(data, tempVal, tempList, 'bar');

          tempVal =  list_parameter[1];
          tempList = list_legend(data, tempVal);
          line_basic_options.legend.data = tempList;
          line_basic_options.series = list_data_series(data, tempVal, tempList, 'line');

          tempVal =  list_parameter[2];
          tempList = list_legend(data, tempVal);
          line_stacked_options.legend.data = tempList;
          line_stacked_options.series = list_data_series(data, tempVal, tempList, 'line');

          tempVal =  list_parameter[3];
          tempList = list_legend(data, tempVal);
          line_basic1_options.legend.data = tempList;
          line_basic1_options.series = list_data_series(data, tempVal, tempList, 'line');

          tempVal =  list_parameter[4];
          tempList = list_legend(data, tempVal);
          line_basic2_options.legend.data = tempList;
          line_basic2_options.series = list_data_series(data, tempVal, tempList, 'line');

          init_chart_element()*/

        },
        error: function(jqXhr, json, errorThrown){// this are default for ajax errors
          var errors = jqXhr.responseJSON;
          var errorsHtml = '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Error ' + jqXhr.status + ': ' + errorThrown + '</div>';
          notificationScript("error", "Error " + jqXhr.status, errorThrown);
          $.each(errors['errors'], function (index, value) {
            errorsHtml += '<div class="alert alert-danger alert-dismissible" role="alert" style="margin-bottom: 0;><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' + value + '</div>';
            notificationScript("error", "Error Field", value);
          });
        }
      }).done(function(){

      });
    }
  // Refresh Charts
  function refresh_charts() {
    f_clear_chart();
    setTimeout(init_chart_value( $("#list_year").val(), $('#list_month').val() ), 5000);
  }

  function init_chart_element() {
    columns_basic.setOption(columns_basic_options, true);
    line_basic.setOption(line_basic_options, true);
    line_basic1.setOption(line_basic1_options, true);
    line_basic2.setOption(line_basic2_options, true);
    line_stacked.setOption(line_stacked_options, true);
  }

  // On Resize
  // window.onresize = function () {
  //   setTimeout(function (){

  //     columns_basic.resize();
  //     line_basic.resize();
  //     line_basic1.resize();
  //     line_basic2.resize();
  //     line_stacked.resize();

  //   }, 200);
  // }

  // Resize function
  var triggerChartResize = function() {
      columns_basic && columns_basic.resize();
      line_basic && line_basic.resize();
      line_basic1 && line_basic1.resize();
      line_basic2 && line_basic2.resize();
      line_stacked && line_stacked.resize();
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

  // Clear Chart Area
  function f_clear_chart(){
    columns_basic.clear();
    line_basic.clear();
    line_basic1.clear();
    line_basic2.clear();
    line_stacked.clear();
  }
</script>
@endsection

@section('content')
<!-- Inner container -->
<div class="d-md-flex align-items-md-start">

  <!-- Left sidebar component -->
  <div
    class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-300 border-0 shadow-0 sidebar-expand-md">

    <!-- Sidebar content -->
    <div class="sidebar-content">

      <!-- Navigation -->
      <div class="card">
        <div class="card-body p-0">
          <ul class="nav nav-sidebar mb-2">
            <li class="nav-item-header">Parameter</li>
            <li class="nav-item">
              <a href="#service_level" class="nav-link active" data-toggle="tab">
                <i class="icon-cog"></i>
                Service Level
                <span class="badge bg-info badge-pill ml-auto" id="sl_val">0%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#fcr" class="nav-link" data-toggle="tab">
                <i class="icon-watch2"></i>
                FCR
                <span class="badge bg-info badge-pill ml-auto" id="fcr_val">0%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#rasio_sales" class="nav-link" data-toggle="tab">
                <i class="icon-clipboard5"></i>
                Rasio Sales
                <span class="badge bg-info badge-pill ml-auto" id="rs_val">0%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#ces" class="nav-link" data-toggle="tab">
                <i class="icon-search4"></i>
                CES (by customer)
                <span class="badge bg-info badge-pill ml-auto" id="ces_val">0%</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#quality_layanan" class="nav-link" data-toggle="tab">
                <i class="icon-thumbs-up2"></i>
                Quality Layanan
                <span class="badge bg-info badge-pill ml-auto" id="ql_val">0%</span>
              </a>
            </li>
            {{-- <li class="nav-item-header">Resource</li>
            <li class="nav-item">
              <a href="#target_bobot_management" class="nav-link" data-toggle="tab">
                <i class="icon-target2"></i><i class="icon-law"></i>
                Target & Bobot Management
              </a>
            </li> --}}
          </ul>
        </div>
      </div>
      <!-- /navigation -->

    </div>
    <!-- /sidebar content -->

  </div>
  <!-- /left sidebar component -->


  <!-- Right content -->
  <div class="tab-content w-100 overflow-auto">
    <div class="tab-pane fade active show" id="service_level">
      <!-- Basic columns -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Service Level [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync" onclick="refresh_charts()"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="columns_basic"></div>
          </div>
        </div>
      </div>
      <!-- /basic columns -->
    </div>

    <div class="tab-pane fade" id="fcr">
      <!-- Basic line -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">FCR [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync" onclick="refresh_charts()"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_basic"></div>
          </div>
        </div>
      </div>
      <!-- /basic line -->
    </div>

    <div class="tab-pane fade" id="rasio_sales">
      <!-- Stacked lines -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Rasio Sales [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync" onclick="refresh_charts()"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_stacked"></div>
          </div>
        </div>
      </div>
      <!-- /stacked lines -->
    </div>

    <div class="tab-pane fade" id="ces">
      <!-- Basic line -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">CES (by customer) [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_basic1"></div>
          </div>
        </div>
      </div>
      <!-- /basic line -->
    </div>

    <div class="tab-pane fade" id="quality_layanan">
      <!-- Basic line -->
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">Quality Layanan [<?php echo date("F - Y"); ?>]</h5>
          <div class="header-elements">
            <button type="button" class="btn bg-blue btn-icon legitRipple ml-3"><i class="icon-sync" onclick="refresh_charts()"></i></button>
            <button type="button" class="btn bg-pink-400 btn-icon ml-3 legitRipple" data-toggle="modal"
              data-target="#insert-new-data"><i class="icon-pencil7"></i></button>
          </div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <div class="chart has-fixed-height" id="line_basic2"></div>
          </div>
        </div>
      </div>
      <!-- /basic line -->
    </div>

    <div class="tab-pane fade" id="target_bobot_management">
      <div class="card">
        <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
          @foreach ($data['parameters_tab'] as $record)
            <li class="nav-item"><a href="#tab-target-bobot-management" class="nav-link" data-toggle="tab" onclick="tab_for_parameter({{ $record->id }})">{{ $record->parameter_desc }}</a>
            </li>
          @endforeach
          {{--
          <li class="nav-item"><a href="#tab-fcr" class="nav-link" data-toggle="tab">FCR</a></li>
          <li class="nav-item"><a href="#tab-rasio_sales" class="nav-link" data-toggle="tab">Rasio Sales</a>
          </li>
          <li class="nav-item"><a href="#tab-ces" class="nav-link" data-toggle="tab">CES (by customer)</a></li>
          <li class="nav-item"><a href="#tab-quality_layanan" class="nav-link" data-toggle="tab">Quality Layanan</a>
          </li>
          --}}
        </ul>

        <div class="tab-content card-body border-top-0 rounded-top-0 mb-0">
          <div class="tab-pane fade" id="tab-target-bobot-management">
            <div class="row">
              <div class="col-md-4">
                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-target2 icon-3x text-success-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0" id="cur_target"></h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Target</span>
                    </div>
                  </div>
                </div>

                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-law icon-3x text-info-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0" id="cur_bobot"></h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Bobot</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <form id="form-target-bobot">
                  <input type="hidden" name="value_parameter" id="value_parameter" value="0">
                  <fieldset>
                    <legend class="font-weight-semibold p-0">
                      <span class="float-left pt-2">
                        <i class="icon-cog mr-2"></i> Setting Target & Bobot <span id="param_name"></span>
                      </span>


                      <div class="float-right pb-1">
                        <button type="submit" class="btn btn-primary btn-sm">Submit <i
                            class="icon-paperplane"></i></button>
                      </div>
                    </legend>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Target</label>
                          <input type="text" class="form-control" autocomplete="off" name="target" id="target">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Satuan</label>
                          <select data-placeholder="Pilih Satuan" class="form-control form-control-select2" data-fouc
                            required>
                            <option></option>
                            <option value="percent">Percent (%)</option>
                            <option value="satuan">Satuan</option>
                            <option value="minutes">minutes</option>
                            <option value="mio">Mio</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Bobot</label>
                          <input type="text" class="form-control" autocomplete="off" name="bobot" id="bobot">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Satuan</label>
                          <select data-placeholder="Pilih Satuan" class="form-control form-control-select2" data-fouc
                            required>
                            <option value="percent">Percent (%)</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </form>
              </div>
            </div>
            <!-- /form inputs -->
          </div>

          {{--
          <div class="tab-pane fade" id="tab-fcr">
            <div class="row">
              <div class="col-md-4">
                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-target2 icon-3x text-success-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0">95%</h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Target</span>
                    </div>
                  </div>
                </div>

                <div class="card-body text-center">
                  <div class="media">
                    <div class="mr-3 align-self-center">
                      <i class="icon-law icon-3x text-info-400"></i>
                    </div>

                    <div class="media-body text-right">
                      <h3 class="font-weight-semibold mb-0">25%</h3>
                      <span class="text-uppercase font-size-sm text-muted">Current Bobot</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <form action="#">
                  <fieldset>
                    <legend class="font-weight-semibold p-0">
                      <span class="float-left pt-2">
                        <i class="icon-cog mr-2"></i> Setting Target & Bobot FCR
                      </span>


                      <div class="float-right pb-1">
                        <button type="submit" class="btn btn-primary btn-sm">Submit <i
                            class="icon-paperplane"></i></button>
                      </div>
                    </legend>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Target</label>
                          <input type="text" class="form-control" autocomplete="off" name="target" id="target">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Satuan</label>
                          <select data-placeholder="Pilih Satuan" class="form-control form-control-select2" data-fouc
                            required>
                            <option></option>
                            <option value="percent">Percent (%)</option>
                            <option value="satuan">Satuan</option>
                            <option value="minutes">minutes</option>
                            <option value="mio">Mio</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Bobot</label>
                          <input type="text" class="form-control" autocomplete="off" name="bobot" id="bobot">
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Satuan</label>
                          <select data-placeholder="Pilih Satuan" class="form-control form-control-select2" data-fouc
                            required>
                            <option value="percent">Percent (%)</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </form>
              </div>
            </div>
            <!-- /form inputs -->
          </div>

          <div class="tab-pane fade" id="tab-rasio_sales">
            DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
          </div>

          <div class="tab-pane fade" id="tab-ces">
            DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
          </div>

          <div class="tab-pane fade" id="tab-quality_layanan">
            DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg whatever.
          </div>
          --}}
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- /right content -->

</div>
<!-- /inner container -->

<!-- Horizontal form modal -->
<div id="insert-new-data" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Insert Data [ <?php $date = date("d - M, Y"); echo date('d - M, Y',(strtotime ( '-1 day' , strtotime ( $date) ) )); ?> ]</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="form-insert-daily" class="form-horizontal">
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-form-label col-sm-5">Date</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" autocomplete="off" name="input_date" id="input_date" required>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Parameter</label>
            <div class="col-sm-7">
              <select data-placeholder="Pilih Parameter" class="form-control form-control-select2"
                name="select_parameter" id="select_parameter" data-fouc required>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Item</label>
            <div class="col-sm-7">
              <select data-placeholder="Pilih Item" class="form-control form-control-select2" name="select_formulasi"
                id="select_formulasi" data-fouc required>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-5">Value</label>
            <div class="col-sm-7">
              <input type="text" class="form-control" autocomplete="off" name="value_formulasi" id="value_formulasi" onkeypress="return numbersonly(event)">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
          <button type="submit" class="btn bg-primary btn-ladda btn-ladda-spinner" data-style="expand-left"
            data-spinner-color="#333" data-spinner-size="20" id="saveBtn"><span
              class="ladda-label">Submit</span></button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /horizontal form modal -->
@endsection
