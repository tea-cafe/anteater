webpackJsonp([22],{807:function(e,n,t){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(n,"__esModule",{value:!0});var r=t(7),o=a(r),c=t(885),u=a(c),i=t(131),s=a(i);t(886);var l=t(313),d=t(314);n.default={namespace:"slot",state:{data:{list:[],pagination:{}},loading:!0},effects:{fetch:s.default.mark(function e(n,t){var a,r=n.payload,o=t.call,c=t.put;return s.default.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,c({type:"changeLoading",payload:!0});case 2:return e.next=4,o(d.querySlot,r);case 4:return a=e.sent,e.next=7,c({type:"save",payload:a.data});case 7:return e.next=9,c({type:"changeLoading",payload:!1});case 9:case"end":return e.stop()}},e,this)}),add:s.default.mark(function e(n,t){var a,r=n.payload,o=n.callback,c=t.call,i=t.put;return s.default.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,c(d.addSlot,r);case 2:if(a=e.sent,0!=a.code){e.next=9;break}return u.default.success("\u6dfb\u52a0\u6210\u529f"),e.next=7,i(l.routerRedux.push("/slot/slist"));case 7:e.next=10;break;case 9:u.default.error("\u6dfb\u52a0\u5931\u8d25:"+a.code+"["+a.msg+"]");case 10:o&&o();case 11:case"end":return e.stop()}},e,this)}),remove:s.default.mark(function e(n,t){var a,r=n.payload,o=n.callback,c=t.call,u=t.put;return s.default.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,u({type:"changeLoading",payload:!0});case 2:return e.next=4,c(d.removeSlot,r);case 4:return a=e.sent,e.next=7,u({type:"save",payload:a});case 7:return e.next=9,u({type:"changeLoading",payload:!1});case 9:o&&o();case 10:case"end":return e.stop()}},e,this)})},reducers:{save:function(e,n){return(0,o.default)({},e,{data:n.payload})},changeLoading:function(e,n){return(0,o.default)({},e,{loading:n.payload})}}},e.exports=n.default},885:function(e,n,t){"use strict";function a(e){if(l)return void e(l);c.a.newInstance({prefixCls:f,transitionName:"move-up",style:{top:s},getContainer:p},function(n){if(l)return void e(l);l=n,e(n)})}function r(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:i,t=arguments[2],r=arguments[3],c={info:"info-circle",success:"check-circle",error:"cross-circle",warning:"exclamation-circle",loading:"loading"}[t];"function"==typeof n&&(r=n,n=i);var s=d++;return a(function(a){a.notice({key:s,duration:n,style:{},content:o.createElement("div",{className:f+"-custom-content "+f+"-"+t},o.createElement(u.default,{type:c}),o.createElement("span",null,e)),onClose:r})}),function(){l&&l.removeNotice(s)}}Object.defineProperty(n,"__esModule",{value:!0});var o=t(5),c=(t.n(o),t(323)),u=t(311),i=3,s=void 0,l=void 0,d=1,f="ant-message",p=void 0;n.default={info:function(e,n,t){return r(e,n,"info",t)},success:function(e,n,t){return r(e,n,"success",t)},error:function(e,n,t){return r(e,n,"error",t)},warn:function(e,n,t){return r(e,n,"warning",t)},warning:function(e,n,t){return r(e,n,"warning",t)},loading:function(e,n,t){return r(e,n,"loading",t)},config:function(e){void 0!==e.top&&(s=e.top,l=null),void 0!==e.duration&&(i=e.duration),void 0!==e.prefixCls&&(f=e.prefixCls),void 0!==e.getContainer&&(p=e.getContainer)},destroy:function(){l&&(l.destroy(),l=null)}}},886:function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var a=t(192),r=(t.n(a),t(932));t.n(r)},932:function(e,n){}});