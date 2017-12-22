@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/MathJax/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script type="text/javascript" src="/page_js/question_edit_new.js"></script>

    <style>
     .node { cursor: pointer;}
     .node circle { fill: #fff;stroke: steelblue;stroke-width: 1.5px;}
     .node text { font: 10px sans-serif;}
     .link { fill: none;stroke: #ccc;stroke-width: 1.5px;}
    </style>
    <section class="content">
        <input value="{{$subject}}" id="subject" type="hidden" />
        <div class="col-xs-1 col-md-1">
            <div class="input-group">
                <button style="margin-left:10px" id="text_book_knowledge" type="button" class="btn btn-primary">返回知识点列表</button>
            </div>
        </div>
        <div class="row">       
            <div class="col-xs-12 col-md-12">
                <div class="knowledge_pic" id="knowledge_pic"></div>
            </div>
        </div>
        <hr/>

    </section>
    <script type="text/javascript" src="/js/d3.v3.min.js"></script>
    <script>
     var margin = {top: 20, right: 40, bottom: 20, left: 40};
     var width = 1920 - margin.right - margin.left;
     var height = 800 - margin.top - margin.bottom;

     var i = 0,
         duration = 750,
         root;

     var tree = d3.layout.tree().size([height, width]);

     var diagonal = d3.svg.diagonal().projection(function(d) { return [d.y, d.x]; });

     var svg = d3.select(".knowledge_pic").append("svg")
                 .attr("width", width + margin.right + margin.left)
                 .attr("height", height + margin.top + margin.bottom)
                 .append("g")
                 .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
     var subject = $("#subject").val();
     d3.json("/question_new/knowledge_get?type=1&subject="+subject, function(error, flare) {
         if (error) throw error;

         root = flare;
         root.x0 = height / 2;
         root.y0 = 0;

         function collapse(d) {
             if (d.children) {
                 d._children = d.children;
                 d._children.forEach(collapse);
                 d.children = null;
             }
         }

         //root.children.forEach(collapse);
         update(root);
     });

     d3.select(self.frameElement).style("height", "1200px");

     function update(source) {

         // Compute the new tree layout.
         var nodes = tree.nodes(root).reverse(),
             links = tree.links(nodes);

         // Normalize for fixed-depth.
         nodes.forEach(function(d) { d.y = d.depth * 180; });

         // Update the nodes…
         var node = svg.selectAll("g.node")
                       .data(nodes, function(d) { return d.id || (d.id = ++i); });

         // Enter any new nodes at the parent's previous position.
         var nodeEnter = node.enter().append("g")
                             .attr("class", "node")
                             .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
                             .on("click", click);

         nodeEnter.append("circle")
                  .attr("r", 1e-6)
                  .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

         nodeEnter.append("text")
                  .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
                  .attr("dy", ".45em")
                  .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
                  .text(function(d) { return d.name; })
                  .style("font-size", '14px')
                  .style("fill-opacity", 1e-6);

         // Transition nodes to their new position.
         var nodeUpdate = node.transition()
                              .duration(duration)
                              .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

         nodeUpdate.select("circle")
                   .attr("r", 4.5)
                   .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

         nodeUpdate.select("text")
                   .style("fill-opacity", 1);

         // Transition exiting nodes to the parent's new position.
         var nodeExit = node.exit().transition()
                            .duration(duration)
                            .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
                            .remove();

         nodeExit.select("circle")
                 .attr("r", 1e-6);

         nodeExit.select("text")
                 .style("fill-opacity", 1e-6);

         // Update the links…
         var link = svg.selectAll("path.link")
                       .data(links, function(d) { return d.target.id; });

         // Enter any new links at the parent's previous position.
         link.enter().insert("path", "g")
             .attr("class", "link")
             .attr("d", function(d) {
                 var o = {x: source.x0, y: source.y0};
                 return diagonal({source: o, target: o});
             });

         // Transition links to their new position.
         link.transition()
             .duration(duration)
             .attr("d", diagonal);

         // Transition exiting nodes to the parent's new position.
         link.exit().transition()
             .duration(duration)
             .attr("d", function(d) {
                 var o = {x: source.x, y: source.y};
                 return diagonal({source: o, target: o});
             })
             .remove();

         // Stash the old positions for transition.
         nodes.forEach(function(d) {
             d.x0 = d.x;
             d.y0 = d.y;
         });
     }

     // Toggle children on click.
     function click(d) {
         if (d.children) {
             d._children = d.children;
             d.children = null;
         } else {
             d.children = d._children;
             d._children = null;
         }
         update(d);
     }


    </script>
@endsection

