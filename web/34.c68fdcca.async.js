webpackJsonp([34],{799:function(t,e,n){"use strict";function a(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var u=n(7),r=a(u),c=n(131),o=a(c),s=n(313),i=n(314);e.default={namespace:"login",state:{status:-1},effects:{accountSubmit:o.default.mark(function t(e,n){var a,u=e.payload,r=n.call,c=n.put;return o.default.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,c({type:"changeSubmitting",payload:!0});case 2:return t.next=4,r(i.accountLogin,u);case 4:if(a=t.sent,0!=a.code){t.next=8;break}return t.next=8,c(s.routerRedux.push("/"));case 8:return t.next=10,c({type:"changeLoginStatus",payload:a});case 10:return t.next=12,c({type:"changeSubmitting",payload:!1});case 12:case"end":return t.stop()}},t,this)}),logout:o.default.mark(function t(e,n){var a,u=n.call,r=n.put;return o.default.wrap(function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,r({type:"changeLoginStatus",payload:{code:-1}});case 2:return t.next=4,u(i.accountLogout);case 4:return a=t.sent,t.next=7,r(s.routerRedux.push("/user/login"));case 7:case"end":return t.stop()}},t,this)})},reducers:{changeLoginStatus:function(t,e){var n=e.payload;return(0,r.default)({},t,{status:n.code,type:"account"})},changeSubmitting:function(t,e){var n=e.payload;return(0,r.default)({},t,{submitting:n})}}},t.exports=e.default}});