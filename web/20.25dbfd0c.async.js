webpackJsonp([20],{803:function(e,t,n){"use strict";function r(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var o=n(7),a=r(o),i=n(131),u=r(i),c=n(892),s=r(c);n(893);var l=n(314),d=n(315);t.default={namespace:"register",state:{status:void 0},effects:{submit:u.default.mark(function e(t,n){var r,o=t.payload,a=n.call,i=n.put;return u.default.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,i({type:"changeSubmitting",payload:!0});case 2:return e.next=4,a(d.register,o);case 4:if(r=e.sent,0!=r.code){e.next=11;break}return s.default.success("\u6ce8\u518c\u6210\u529f,\u81ea\u52a8\u8df3\u8f6c\u767b\u5f55\u9875\u9762"),e.next=9,i(l.routerRedux.push("/"));case 9:e.next=12;break;case 11:s.default.error("\u6ce8\u518c\u5931\u8d25:"+r.code+"["+r.msg+"]");case 12:return e.next=14,i({type:"registerHandle",payload:r});case 14:return e.next=16,i({type:"changeSubmitting",payload:!1});case 16:case"end":return e.stop()}},e,this)})},reducers:{registerHandle:function(e,t){var n=t.payload;return(0,a.default)({},e,{status:n.status})},changeSubmitting:function(e,t){var n=t.payload;return(0,a.default)({},e,{submitting:n})}}},e.exports=t.default},892:function(e,t,n){"use strict";function r(e){if(l)return void e(l);i.a.newInstance({prefixCls:f,transitionName:"move-up",style:{top:s},getContainer:p},function(t){if(l)return void e(l);l=t,e(t)})}function o(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:c,n=arguments[2],o=arguments[3],i={info:"info-circle",success:"check-circle",error:"cross-circle",warning:"exclamation-circle",loading:"loading"}[n];"function"==typeof t&&(o=t,t=c);var s=d++;return r(function(r){r.notice({key:s,duration:t,style:{},content:a.createElement("div",{className:f+"-custom-content "+f+"-"+n},a.createElement(u.default,{type:i}),a.createElement("span",null,e)),onClose:o})}),function(){l&&l.removeNotice(s)}}Object.defineProperty(t,"__esModule",{value:!0});var a=n(5),i=(n.n(a),n(326)),u=n(312),c=3,s=void 0,l=void 0,d=1,f="ant-message",p=void 0;t.default={info:function(e,t,n){return o(e,t,"info",n)},success:function(e,t,n){return o(e,t,"success",n)},error:function(e,t,n){return o(e,t,"error",n)},warn:function(e,t,n){return o(e,t,"warning",n)},warning:function(e,t,n){return o(e,t,"warning",n)},loading:function(e,t,n){return o(e,t,"loading",n)},config:function(e){void 0!==e.top&&(s=e.top,l=null),void 0!==e.duration&&(c=e.duration),void 0!==e.prefixCls&&(f=e.prefixCls),void 0!==e.getContainer&&(p=e.getContainer)},destroy:function(){l&&(l.destroy(),l=null)}}},893:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=n(192),o=(n.n(r),n(936));n.n(o)},936:function(e,t){}});