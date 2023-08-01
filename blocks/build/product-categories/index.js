(()=>{"use strict";var e,t={67:(e,t,l)=>{const o=window.wp.blocks,r=window.wp.element,n=window.wp.i18n,a=window.wp.blockEditor,i=window.wp.serverSideRender;var s=l.n(i);const h=window.wp.components,p=[{value:"standard",label:"Page navigation"},{value:"ajax",label:"Async navigation (ajax)"},{value:"button",label:"Apply filters button"}],c=({attributes:e,onChange:t=(()=>{})})=>{const{navigation:l,group:o}=e;return(0,r.createElement)(h.PanelBody,{title:(0,n.__)("Filtering settings","shoophilters"),initialOpen:!0},(0,r.createElement)(h.PanelRow,null,(0,r.createElement)(h.SelectControl,{label:(0,n.__)("Filter navigation type","shoophilters"),help:(0,n.__)("Choose between url navigation, ajax navigation or delegate filters to a button.","shoophilters"),value:l,onChange:e=>t({navigation:e,group:o}),options:p})),(0,r.createElement)(h.PanelRow,null,(0,r.createElement)(h.TextControl,{label:(0,n.__)("Filter group id","shoophilters"),help:(0,n.__)("The filter group id to apply. All filters in the same group will be applied and reseted at the same time.","shoophilters"),type:"text",value:o,onChange:e=>{return t({navigation:l,group:(o=e,(o||"").replace(/ /g,"-").replace(/--/g,"-").replace(/^[0-9]/,"").replace(/^[-]/,"").replace(/[^A-Za-z0-9-]/g,"").toLowerCase()||"default")});var o}})))},u=[{value:"never",label:(0,n.__)("Never","shoophilters")},{value:"always",label:(0,n.__)("Always","shoophilters")},{value:"current",label:(0,n.__)("Only when selected","shoophilters")}];(0,n.__)("Fixed","shoophilters"),(0,n.__)("Calculated","shoophilters");const g=JSON.parse('{"u2":"shoophilters/product-categories"}');(0,o.registerBlockType)(g.u2,{edit:function(e){const{attributes:t,setAttributes:l}=e;return(0,r.createElement)("div",{...(0,a.useBlockProps)()},(0,r.createElement)(r.Fragment,null,(0,r.createElement)(a.InspectorControls,null,(0,r.createElement)(h.PanelBody,{title:(0,n.__)("Category settings","shoophilters"),initialOpen:!0},(0,r.createElement)(h.PanelRow,null,(0,r.createElement)(h.ToggleControl,{label:(0,n.__)("Show empty categories","shoophilters"),checked:t.showEmpty,onChange:e=>l({showEmpty:e})})),(0,r.createElement)(h.PanelRow,null,(0,r.createElement)(h.ToggleControl,{label:(0,n.__)("Show child categories","shoophilters"),checked:t.showChildren,onChange:e=>l({showChildren:e})})),(0,r.createElement)(h.PanelRow,null,(0,r.createElement)(h.SelectControl,{label:(0,n.__)("Show product count","shoophilters"),value:t.showTotal,onChange:e=>l({showTotal:e}),options:u}))),(0,r.createElement)(c,{attributes:t.filtering,onChange:e=>l({filtering:e})})),(0,r.createElement)("div",null,(0,r.createElement)(s(),{block:"shoophilters/product-categories",attributes:e.attributes}))))}})}},l={};function o(e){var r=l[e];if(void 0!==r)return r.exports;var n=l[e]={exports:{}};return t[e](n,n.exports,o),n.exports}o.m=t,e=[],o.O=(t,l,r,n)=>{if(!l){var a=1/0;for(p=0;p<e.length;p++){l=e[p][0],r=e[p][1],n=e[p][2];for(var i=!0,s=0;s<l.length;s++)(!1&n||a>=n)&&Object.keys(o.O).every((e=>o.O[e](l[s])))?l.splice(s--,1):(i=!1,n<a&&(a=n));if(i){e.splice(p--,1);var h=r();void 0!==h&&(t=h)}}return t}n=n||0;for(var p=e.length;p>0&&e[p-1][2]>n;p--)e[p]=e[p-1];e[p]=[l,r,n]},o.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return o.d(t,{a:t}),t},o.d=(e,t)=>{for(var l in t)o.o(t,l)&&!o.o(e,l)&&Object.defineProperty(e,l,{enumerable:!0,get:t[l]})},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={317:0,494:0};o.O.j=t=>0===e[t];var t=(t,l)=>{var r,n,a=l[0],i=l[1],s=l[2],h=0;if(a.some((t=>0!==e[t]))){for(r in i)o.o(i,r)&&(o.m[r]=i[r]);if(s)var p=s(o)}for(t&&t(l);h<a.length;h++)n=a[h],o.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return o.O(p)},l=self.webpackChunkshoophilters=self.webpackChunkshoophilters||[];l.forEach(t.bind(null,0)),l.push=t.bind(null,l.push.bind(l))})();var r=o.O(void 0,[494],(()=>o(67)));r=o.O(r)})();