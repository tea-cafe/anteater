webpackJsonp([31],{795:function(e,t,a){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var r=a(7),u=n(r),s=a(131),o=n(s),c=a(315);t.default={namespace:"forget",state:{status:void 0,token:""},effects:{send:o.default.mark(function e(t,a){var n,r=t.payload,u=a.call,s=a.put;return o.default.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,u(c.mailForget,r);case 2:return n=e.sent,e.next=5,s({type:"registerHandle",payload:n});case 5:case"end":return e.stop()}},e,this)}),submit:o.default.mark(function e(t,a){var n,r=t.payload,u=a.call,s=a.put;return o.default.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s({type:"changeSubmitting",payload:!0});case 2:return e.next=4,u(c.verifyForget,r);case 4:return n=e.sent,e.next=7,s({type:"setToken",payload:n});case 7:return e.next=9,s({type:"changeSubmitting",payload:!1});case 9:case"end":return e.stop()}},e,this)}),reset:o.default.mark(function e(t,a){var n,r=t.payload,u=a.call,s=a.put;return o.default.wrap(function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,s({type:"changeSubmitting",payload:!0});case 2:return e.next=4,u(c.resetForget,r);case 4:return n=e.sent,e.next=7,s({type:"registerHandle",payload:n});case 7:return e.next=9,s({type:"changeSubmitting",payload:!1});case 9:case"end":return e.stop()}},e,this)})},reducers:{registerHandle:function(e,t){var a=t.payload;return(0,u.default)({},e,{status:a.status})},setToken:function(e,t){var a=t.payload;return(0,u.default)({},e,{token:a.data.strToken})},changeSubmitting:function(e,t){var a=t.payload;return(0,u.default)({},e,{submitting:a})}}},e.exports=t.default}});