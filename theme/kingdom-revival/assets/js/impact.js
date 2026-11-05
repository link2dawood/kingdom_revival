(function () {
  "use strict";
  var node = document.getElementById("krm-impact-data");
  if (!node) return;
  var data;
  try { data = JSON.parse(node.textContent); } catch (e) { return; }
  /* Chart colours come from the theme's CSS variables (light or dark), read again whenever the theme changes. */
  var colors = [], grid = "";
  function readColors() {
    var cs = getComputedStyle(document.documentElement), v = function (n) { return cs.getPropertyValue(n).trim(); };
    colors = [v("--color-chart-1"), v("--color-chart-2"), v("--color-chart-3"), v("--color-chart-4")];
    grid = v("--color-chart-grid");
  }
  readColors();
  var esc = function (s) { return String(s).replace(/[&<>\"]/g, function (c) { return {"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]; }); };
  var num = function (v) { return Number(v).toLocaleString("en-US"); };
  /* Without data: administrators see a note (data.showPending); visitors see nothing (the element is hidden). */
  var empty = function (el, text) {
    if (!data.showPending) { el.innerHTML = ""; el.hidden = true; return; }
    el.innerHTML = '<p class="impact-chart__empty">' + esc(text || "Awaiting verified yearly figures") + "</p>";
  };

  function bars(el, rows, label, dark) {
    if (!el || !rows || !rows.length) { if (el) empty(el); return; }
    var good = rows.filter(function (r) { return r.value !== null && r.value !== "" && Number.isFinite(Number(r.value)); });
    if (!good.length) { empty(el); return; }
    el.hidden = false;
    var max = Math.max.apply(null, good.map(function (r) { return Number(r.value); })) || 1;
    var w = 720, h = 260, base = 220, gap = w / good.length, bw = Math.min(54, gap * .56);
    var svg = '<div class="impact-chart__visual"><svg viewBox="0 0 '+w+' '+h+'" role="img" aria-label="'+esc(label)+'">';
    [0,.25,.5,.75,1].forEach(function (p) { var y=base-(p*180); svg += '<line x1="35" y1="'+y+'" x2="710" y2="'+y+'" stroke="'+grid+'" opacity=".45"/><text x="2" y="'+(y+4)+'">'+num(Math.round(max*p))+'</text>'; });
    good.forEach(function (r,i) { var bh=Number(r.value)/max*180,x=35+i*gap+(gap-bw)/2,y=base-bh; svg += '<rect data-tip="'+esc(r.year+': '+num(r.value))+'" x="'+x+'" y="'+y+'" width="'+bw+'" height="'+bh+'" rx="4" fill="'+(dark?colors[1]:colors[i%3])+'"><title>'+esc(r.year+': '+num(r.value))+'</title></rect><text text-anchor="middle" x="'+(x+bw/2)+'" y="242">'+esc(r.year)+'</text>'; });
    svg += '</svg></div><table><caption>'+esc(label)+' data</caption><thead><tr><th>Year</th><th>Value</th></tr></thead><tbody>' + good.map(function(r){return '<tr><th>'+esc(r.year)+'</th><td>'+num(r.value)+'</td></tr>';}).join("") + '</tbody></table>';
    el.innerHTML = svg;
  }

  function series(el, list) {
    if (!el || !list || !list.length) { if (el) empty(el, "Growth data will appear when verified yearly figures are added."); return; }
    var years = [], max = 1;
    list.forEach(function(s){(s.values||[]).forEach(function(v){if(years.indexOf(v.year)<0)years.push(v.year);max=Math.max(max,Number(v.value)||0);});});
    if (!years.length) { empty(el); return; }
    var w=900,h=360,pad=55,dx=(w-pad*2)/Math.max(1,years.length-1),dy=(h-100)/max,svg='<div class="impact-chart__visual"><svg viewBox="0 0 '+w+' '+h+'" role="img" aria-label="Growth over the years">';
    years.forEach(function(y,i){svg+='<text text-anchor="middle" x="'+(pad+i*dx)+'" y="330">'+esc(y)+'</text>';});
    list.forEach(function(s,si){var pts=(s.values||[]).map(function(v,i){return(pad+i*dx)+','+(290-Number(v.value)*dy);}).join(' ');svg+='<polyline points="'+pts+'" fill="none" stroke="'+colors[si%colors.length]+'" stroke-width="5"/><text x="'+(pad+si*180)+'" y="24" fill="'+colors[si%colors.length]+'">'+esc(s.label)+'</text>';});
    svg+='</svg></div><table><caption>Growth data</caption><thead><tr><th>Metric</th>'+years.map(function(y){return '<th>'+esc(y)+'</th>';}).join('')+'</tr></thead><tbody>'+list.map(function(s){return '<tr><th>'+esc(s.label)+'</th>'+years.map(function(y){var v=(s.values||[]).filter(function(x){return x.year===y;})[0];return '<td>'+(v?num(v.value):'—')+'</td>';}).join('')+'</tr>';}).join('')+'</tbody></table>';el.innerHTML=svg;
  }

  var tabs = Array.prototype.slice.call(document.querySelectorAll("[data-impact-index]"));
  var panel = document.getElementById("impact-ministry-panel");
  function select(i, focus) {
    var m=data.ministries[i]; if(!m||!panel)return; current=i;
    tabs.forEach(function(t,j){t.setAttribute("aria-selected",j===i?"true":"false");t.tabIndex=j===i?0:-1;});
    panel.setAttribute("aria-labelledby","impact-tab-"+i);
    panel.querySelector("[data-impact-group]").textContent=m.group;panel.querySelector("[data-impact-name]").textContent=m.name;
    var metrics=panel.querySelector("[data-impact-metrics]");metrics.hidden=!m.keyMetrics.length&&!data.showPending;metrics.innerHTML=m.keyMetrics.length?m.keyMetrics.map(function(x){return '<div><strong>'+esc(num(x.value))+'</strong><span>'+esc(x.label)+'</span></div>';}).join(''):(data.showPending?'<p class="impact-pending">Awaiting verified figure</p>':'');
    panel.querySelector("[data-impact-locations]").textContent=m.locations.length?m.locations.join(", "):(data.showPending?"To be supplied":"");panel.querySelector(".impact-locations").hidden=!m.locations.length&&!data.showPending;
    panel.querySelector("[data-impact-support]").href=(window.KRM_CONFIG&&KRM_CONFIG.donateUrl?KRM_CONFIG.donateUrl:"/donate/")+"?ministry="+encodeURIComponent(m.slug)+"#give";
    bars(panel.querySelector("[data-impact-ministry-chart]"),m.yearlyValues,m.name+" yearly impact",true);if(focus)panel.focus();
  }
  tabs.forEach(function(t,i){t.addEventListener("click",function(){select(i,false);});t.addEventListener("keydown",function(e){var n=e.key==="ArrowDown"||e.key==="ArrowRight"?i+1:e.key==="ArrowUp"||e.key==="ArrowLeft"?i-1:null;if(n===null)return;e.preventDefault();n=(n+tabs.length)%tabs.length;tabs[n].focus();select(n,false);});});select(0,false);
  var current = 0;
  function drawCharts() {
    series(document.querySelector("[data-impact-growth]"),data.growth.series);
    bars(document.querySelector("[data-impact-freedom]"),data.freedom.familiesFreedPerYear,"Families freed per year",false);
  }
  drawCharts();
  document.addEventListener("krm:themechange", function () { readColors(); drawCharts(); select(current, false); });
  var financial=document.querySelector("[data-impact-financial]");if(financial){var valid=data.financials.filter(function(x){return x.percent !== null && x.percent !== "" && Number.isFinite(Number(x.percent));});financial.innerHTML=valid.length?valid.map(function(x){return '<div class="impact-financial__row"><div class="impact-financial__label"><span>'+esc(x.label)+'</span><span>'+esc(x.percent)+'%</span></div><div class="impact-financial__track"><div class="impact-financial__fill" style="width:'+Math.max(0,Math.min(100,Number(x.percent)))+'%"></div></div></div>';}).join(''):'<p class="impact-chart__empty">Financial allocation percentages awaiting annual accounts.</p>';}
})();
