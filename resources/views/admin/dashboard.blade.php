@extends('layouts/app')

@section('title', 'Daily All Site')

@section('liblary')
<script src="assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="assets/js/plugins/forms/selects/select2.min.js"></script>
<script src="assets/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="assets/js/plugins/visualization/d3/d3.min.js"></script>
<script src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
@endsection

@section('extra-liblary')
<script src="assets/js/demo_pages/form_select2.js"></script>
<script src="assets/js/demo_pages/form_layouts.js"></script>
{{--<script src="assets/js/demo_pages/widgets_stats.js"></script>--}}
@endsection

@section('script')
<script type="text/javascript">
  var id_layanan = [
  @foreach ($list_layanan as $layanan)
    {{ $layanan->id }},
  @endforeach
  ];
  var layanan_tperf = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_tperf',
  @endforeach
  ];
  var layanan_segmented_gauge = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_segmented_gauge',
  @endforeach
  ];
  var layanan_tbobot = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_tbobot',
  @endforeach
  ];
  var layanan_kpi_name = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_kpi_name',
  @endforeach
  ];
  var layanan_kpi_param = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_kpi_param',
  @endforeach
  ];
  var layanan_summary = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_summary',
  @endforeach
  ];
  var layanan_chart_bar = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_chart_bar',
  @endforeach
  ];
  var layanan_avg_perf = [
  @foreach ($list_layanan as $layanan)
    'lay_{{ $layanan->id }}_avg_perf',
  @endforeach
  ];
  //Inisiasi BlockUI
  $('.card-body').block({
      message: '<i class="icon-spinner4 spinner"></i>',
      overlayCSS: {
          backgroundColor: '#fff',
          opacity: 0.95,
          cursor: 'wait'
      },
      css: {
          border: 0,
          padding: 0,
          backgroundColor: 'transparent'
      }
  });

  function trans_val(data, key) {
    var resArr = [];
    data.filter(function(item){
      var i = resArr.findIndex(x => (x[key] == item[key]));
      if(i <= -1){
            resArr.push(item);
      }
      return null;
    });
    var retArr = []
    for (var i = 0; i < resArr.length; i++) {
      retArr.push(resArr[i][key])
    }
    return retArr;
  }
  function titleCase(string) {
    var sentence = string.toLowerCase().split(" ");
    for(var i = 0; i< sentence.length; i++){
      sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
    }
    document.write(sentence.join(" "));
    return sentence;
   }


  // Func
  function get_kpi_information_progress(year_value, month_value, id_layanan, idx) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-kpi-information-progress',
         data: {year: year_value, month: month_value, layanan: id_layanan},
         success: function(data){
          var content_kpi_name = '<li><span class="font-weight-semibold">Target KPI</span></li>';
          var content_kpi_param = '<li><span class="font-weight-semibold">Param</span></li>';
          for (var i = 0; i < data.length; i++) {
            content_kpi_name += '<li>'+ data[i]['parameter_desc'] +'</li>';
            content_kpi_param += '<li>'+ data[i]['target'] +'</li>';
          }
          $('#'+layanan_kpi_name[idx]).html(content_kpi_name);
          $('#'+layanan_kpi_param[idx]).html(content_kpi_param);
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

  function get_summary_layanan(year_value, month_value, id_layanan, idx) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-summary-layanan',
         data: {year: year_value, month: month_value, layanan: id_layanan},
         success: function(data){
          //console.log(data[0])
          var content = '';
          var t_bobot_val = 0;
          var t_perf_val = 0;
          for (var i = 0; i < data.length; i++) {
            content += '<tr>';
            content += '<td class="font-weight-semibold">'+ data[i]['parameter_desc'] +'</td>';
            content += '<td class="font-weight-semibold text-right">'+ data[i]['target'] +'</td>';
            content += '<td class="font-weight-semibold text-right">'+ data[i]['realisasi'] + ' ' + data[i]['satuan'] +'</td>';
            content += '<td class="font-weight-semibold text-right">'+ data[i]['achievement'] +'%</td>';
            content += '<td class="font-weight-semibold text-right">'+ data[i]['perfomance'] +'%</td>';
            content += '</tr>';
            t_bobot_val += data[i]['persetasi_bobot'];
            t_perf_val += data[i]['perfomance'];
          }
          $('#'+ layanan_summary[idx] +' tbody').html(content);
          $('#'+ layanan_tperf[idx] ).html(t_perf_val);
          // $('#'+ layanan_tbobot[idx] ).html(t_bobot_val);
          // _segmentedGauge('#'+layanan_segmented_gauge[idx], 200, 0, 100, 5, t_bobot_val);
          $('#'+ layanan_tbobot[idx] ).html(t_perf_val);
          _segmentedGauge('#'+layanan_segmented_gauge[idx], 200, 0, 100, 5, t_perf_val);
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
        $('.card-body').unblock();
      });
  }

  // On change events
  $("#list_month").change(function() {
    var id = $(this).val();
    tempMonth = id;
    if (id != "" && id != null)
    {
      setTimeout(re_init(), 5000);
    }
  });

  // Func re inisiasi tampulan
  function re_init() {
    //get_perfomance_comparison( $("#list_year").val(), $("#list_month").val() );
    for (var i = 0; i < id_layanan.length; i++) {
      get_perfomance_comparison( $("#list_year").val(), $("#list_month").val(), id_layanan[i], i );
      get_kpi_information_progress( $("#list_year").val(), $("#list_month").val(), id_layanan[i], i );
      get_summary_layanan( $("#list_year").val(), $("#list_month").val(), id_layanan[i], i );
    }
  }
  $(document).ready(function() {
    chain3();
  });
</script>
<script type="text/javascript">
  function get_perfomance_comparison(year_value, month_value, id_layanan, idx) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
     type:"post",
     url:'/get-perfomance-comparison',
     data: {year: year_value, month: month_value, layanan: id_layanan},
         success: function(data){
          console.log(data)
          _barChartWidget('#'+layanan_chart_bar[idx], 24, 50, true, "elastic", 1200, 50, "#EF5350", "months", data);
          var total = 0;
          for (var i = 0; i < data.length; i++) {
            total += data[i]['total_perfomance'];
          }
          if (total == 0) {avg = 0;}
          else{avg = parseFloat(total/12).toFixed(2);}
          $('#'+layanan_avg_perf[idx]).html(avg+'%')
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
</script>
<script type="text/javascript">
  function _segmentedGauge(element, size, min, max, sliceQty, t_bobot_val) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {
            $(element).html('');
            // Main variables
            var d3Container = d3.select(element),
                width = size,
                height = (size / 2) + 20,
                radius = (size / 2),
                ringInset = 15,
                ringWidth = 20,

                pointerWidth = 10,
                pointerTailLength = 5,
                pointerHeadLengthPercent = 0.75,

                minValue = min,
                maxValue = max,

                minAngle = -90,
                maxAngle = 90,

                slices = sliceQty,
                range = maxAngle - minAngle,
                pointerHeadLength = Math.round(radius * pointerHeadLengthPercent);

            // Colors
            var colors = d3.scale.linear()
                .domain([0, slices - 1])
                .interpolate(d3.interpolateHsl)
                .range(['#EF5350', '#66BB6A']);


            // Create chart
            // ------------------------------

            // Add SVG element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                .attr('width', width)
                .attr('height', height);


            // Construct chart layout
            // ------------------------------

            // Donut
            var arc = d3.svg.arc()
                .innerRadius(radius - ringWidth - ringInset)
                .outerRadius(radius - ringInset)
                .startAngle(function(d, i) {
                    var ratio = d * i;
                    return deg2rad(minAngle + (ratio * range));
                })
                .endAngle(function(d, i) {
                    var ratio = d * (i + 1);
                    return deg2rad(minAngle + (ratio * range));
                });

            // Linear scale that maps domain values to a percent from 0..1
            var scale = d3.scale.linear()
                .range([0, 1])
                .domain([minValue, maxValue]);

            // Ticks
            var ticks = scale.ticks(slices);
            var tickData = d3.range(slices)
                .map(function() {
                    return 1 / slices;
                });

            // Calculate angles
            function deg2rad(deg) {
                return deg * Math.PI / 180;
            }

            // Calculate rotation angle
            function newAngle(d) {
                var ratio = scale(d);
                var newAngle = minAngle + (ratio * range);
                return newAngle;
            }


            // Append chart elements
            // ------------------------------

            //
            // Append arc
            //

            // Wrap paths in separate group
            var arcs = svg.append('g')
                .attr('transform', "translate(" + radius + "," + radius + ")")
                .style({
                    'stroke': '#fff',
                    'stroke-width': 2,
                    'shape-rendering': 'crispEdges'
                });

            // Add paths
            arcs.selectAll('path')
                .data(tickData)
                .enter()
                .append('path')
                .attr('fill', function(d, i) {
                    return colors(i);
                })
                .attr('d', arc);


            //
            // Text labels
            //

            // Wrap text in separate group
            var arcLabels = svg.append('g')
                .attr('transform', "translate(" + radius + "," + radius + ")");

            // Add text
            arcLabels.selectAll('text')
                .data(ticks)
                .enter()
                .append('text')
                .attr('transform', function(d) {
                    var ratio = scale(d);
                    var newAngle = minAngle + (ratio * range);
                    return 'rotate(' + newAngle + ') translate(0,' + (10 - radius) + ')';
                })
                .style({
                    'text-anchor': 'middle',
                    'font-size': 11,
                    'fill': '#999'
                })
                .text(function(d) { return d + "%"; });


            //
            // Pointer
            //

            // Line data
            var lineData = [
                [pointerWidth / 2, 0],
                [0, -pointerHeadLength],
                [-(pointerWidth / 2), 0],
                [0, pointerTailLength],
                [pointerWidth / 2, 0]
            ];

            // Create line
            var pointerLine = d3.svg.line()
                .interpolate('monotone');

            // Wrap all lines in separate group
            var pointerGroup = svg
                .append('g')
                .data([lineData])
                .attr('transform', "translate(" + radius + "," + radius + ")");

            // Paths
            pointer = pointerGroup
                .append('path')
                .attr('d', pointerLine)
                .attr('transform', 'rotate(' + minAngle + ')');


            // Random update
            // ------------------------------

            // Update values
            function update(value) {
                var ratio = scale(value);
                var newAngle = minAngle + (ratio * range);
                pointer.transition()
                    .duration(2500)
                    .ease('elastic')
                    .attr('transform', 'rotate(' + newAngle + ')');
            }
            update(t_bobot_val);

            // Update values every 5 seconds
            // setInterval(function() {
            //     update();
            // }, 5000);
        }
    };

    function _barChartWidget(element, barQty, height, animate, easing, duration, delay, color, tooltip, data) {
        if (typeof d3 == 'undefined') {
            console.warn('Warning - d3.min.js is not loaded.');
            return;
        }

        // Initialize chart only if element exsists in the DOM
        if(element) {
            $(element).html('');

            // Basic setup
            // ------------------------------

            // Add data set
            var bardata = [];
            for (var i = 0; i < data.length; i++) {
              bardata.push(data[i]['total_perfomance'])
            }

            // Main variables
            var d3Container = d3.select(element),
                width = d3Container.node().getBoundingClientRect().width;



            // Construct scales
            // ------------------------------

            // Horizontal
            var x = d3.scale.ordinal()
                .rangeBands([0, width], 0.3);

            // Vertical
            var y = d3.scale.linear()
                .range([0, height]);



            // Set input domains
            // ------------------------------

            // Horizontal
            x.domain(d3.range(0, bardata.length));

            // Vertical
            y.domain([0, d3.max(bardata)]);



            // Create chart
            // ------------------------------

            // Add svg element
            var container = d3Container.append('svg');

            // Add SVG group
            var svg = container
                .attr('width', width)
                .attr('height', height)
                .append('g');



            //
            // Append chart elements
            //

            // Bars
            var bars = svg.selectAll('rect')
                .data(bardata)
                .enter()
                .append('rect')
                    .attr('class', 'd3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    })
                    .style('fill', color);



            // Tooltip
            // ------------------------------

            // Initiate
            var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0]);

            // Show and hide
            if(tooltip == "months") {
                bars.call(tip)
                    .on('mouseover', tip.show)
                    .on('mouseout', tip.hide);
            }

            // Online members tooltip content
            if(tooltip == "months") {
                tip.html(function (d, i) {
                    return "<div class='text-center'>" +
                            "<h6 class='mb-0'>" + d + "%" + "</h6>" +
                            "<span class='font-size-sm'>" + data[i]['desc'] + "</span>" +
                        "</div>";
                });
            }



            // Bar loading animation
            // ------------------------------

            // Choose between animated or static
            if(animate) {
                withAnimation();
            } else {
                withoutAnimation();
            }

            // Animate on load
            function withAnimation() {
                bars
                    .attr('height', 0)
                    .attr('y', height)
                    .transition()
                        .attr('height', function(d) {
                            return y(d);
                        })
                        .attr('y', function(d) {
                            return height - y(d);
                        })
                        .delay(function(d, i) {
                            return i * delay;
                        })
                        .duration(duration)
                        .ease(easing);
            }

            // Load without animateion
            function withoutAnimation() {
                bars
                    .attr('height', function(d) {
                        return y(d);
                    })
                    .attr('y', function(d) {
                        return height - y(d);
                    });
            }



            // Resize chart
            // ------------------------------

            // Call function on window resize
            $(window).on('resize', barsResize);

            // Call function on sidebar width change
            $(document).on('click', '.sidebar-control', barsResize);

            // Resize function
            //
            // Since D3 doesn't support SVG resize by default,
            // we need to manually specify parts of the graph that need to
            // be updated on window resize
            function barsResize() {

                // Layout variables
                width = d3Container.node().getBoundingClientRect().width;


                // Layout
                // -------------------------

                // Main svg width
                container.attr("width", width);

                // Width of appended group
                svg.attr("width", width);

                // Horizontal range
                x.rangeBands([0, width], 0.3);


                // Chart elements
                // -------------------------

                // Bars
                svg.selectAll('.d3-random-bars')
                    .attr('width', x.rangeBand())
                    .attr('x', function(d,i) {
                        return x(i);
                    });
            }
        }
    };
</script>
@endsection

@section('content')
<div class="row">
  {{-- Iterasi disini mulainya --}}
  @php
  $border_color = ['danger', 'primary', 'success', 'warning', 'info'];
  $x = 0;
  @endphp
  @foreach ($list_layanan as $layanan)
  <div class="col-lg-6">
    <div class="card border-left-3 border-left-@php echo $border_color[$x]; @endphp rounded-left-0">
      <div class="card-header bg-white header-elements-inline p-2">
        <h4 class="card-title font-weight-semibold">Layanan {{ $layanan->layanan_desc }}</h4>
        <div class="header-elements">
          <ul class="list-inline list-inline-dotted mb-0">
            <li class="list-inline-item"><span class="font-size-lg font-weight-bold">Total Perf</span></li>
            <li class="list-inline-item"><span
                class="badge badge-@php echo $border_color[$x]; $x++; @endphp font-size-lg"><span
                  id="lay_{{ $layanan->id }}_tperf">0</span>%</span></li>
          </ul>
        </div>
      </div>
      <div class="card-body pl-2 pt-2 pr-2 pb-0">
        <div class="row">
          <div class="col-md-4">
            
            <div class="svg-center" id="lay_{{ $layanan->id }}_segmented_gauge" style="border-bottom:1px solid #999;">
            </div>

            <h4 class="text-center">Perfomance <span id="lay_{{ $layanan->id }}_tbobot">0</span>%</h4>
            {{--
            <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
              <div>
                <ul class="list list-unstyled mb-0" id="lay_{{ $layanan->id }}_kpi_name">
                  <li><span class="font-weight-semibold">Target KPI</span></li>
                  <li>Service Level</li>
                  <li>FCR</li>
                  <li>Rasio Sales</li>
                  <li>CES</li>
                  <li>Quality</li>
                </ul>
              </div>

              <div class="text-sm-right mb-0 mt-3 mt-sm-0 ml-auto">
                <ul class="list list-unstyled mb-0" id="lay_{{ $layanan->id }}_kpi_param">
                  <li><span class="font-weight-semibold">Param</span></li>
                  <li>>=95%</li>
                  <li>>=90%</li>
                  <li>>=15%</li>
                  <li>>=90% Puas</li>
                  <li>
                    <=5% NOK</li> </ul> 
                  </div> 
            </div> 
            --}}
          </div> 
          <div class="col-md-8">
            <div class="table-responsive">
              <table class="table text-nowrap table-xs table-borderless" id="lay_{{ $layanan->id }}_summary">
                <thead>
                  <tr>
                    <td class="font-weight-bold font-size-lg w-100">KPI</td>
                    <td class="font-weight-bold font-size-lg w-100">Target</td>
                    <td class="font-weight-bold font-size-lg text-right">Real</td>
                    <td class="font-weight-bold font-size-lg text-right">Achv</td>
                    <td class="font-weight-bold font-size-lg text-right">Perf</td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="font-weight-semibold">Service Level</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                  </tr>
                  <tr>
                    <td class="font-weight-semibold">FCR</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                  </tr>
                  <tr>
                    <td class="font-weight-semibold">Rasio Sales</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                  </tr>
                  <tr>
                    <td class="font-weight-semibold">CES (by Customer)</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                  </tr>
                  <tr>
                    <td class="font-weight-semibold">Quality Layanan</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                    <td class="font-weight-semibold text-right">0%</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="container-fluid">
            {{-- <div id="chart_bar_basic"></div> --}}
            <div class="ml-3" style="border-top:1px solid #666;">
              <div class="col-md-12 mb-2">
                <div class="d-flex">
                  <h5 class="font-weight-semibold mb-0">Year To Day</h5>
                  <span class="badge bg-danger-800 badge-pill align-self-center ml-auto"
                  id="lay_{{ $layanan->id }}_avg_perf">Perf</span>
                </div>
              </div>
            </div>
            <div id="lay_{{ $layanan->id }}_chart_bar"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
