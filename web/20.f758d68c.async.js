webpackJsonp([20],{1354:function(e,t,n){"use strict";function o(e){var t=e[e.length-1];if(t)return t.title}function r(e){var t=e||"";t!==document.title&&(document.title=t)}function a(){}var u=n(5),i=n(8),l=n(1355);a.prototype=Object.create(u.Component.prototype),a.displayName="DocumentTitle",a.propTypes={title:i.string.isRequired},a.prototype.render=function(){return this.props.children?u.Children.only(this.props.children):null},e.exports=l(o,r)(a)},1355:function(e,t,n){"use strict";function o(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function a(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function u(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var i=n(5),l=o(i),c=n(1356),f=o(c),d=n(837),p=o(d);e.exports=function(e,t,n){function o(e){return e.displayName||e.name||"Component"}if("function"!=typeof e)throw new Error("Expected reducePropsToState to be a function.");if("function"!=typeof t)throw new Error("Expected handleStateChangeOnClient to be a function.");if(void 0!==n&&"function"!=typeof n)throw new Error("Expected mapStateOnServer to either be undefined or a function.");return function(c){function d(){h=e(s.map(function(e){return e.props})),m.canUseDOM?t(h):n&&(h=n(h))}if("function"!=typeof c)throw new Error("Expected WrappedComponent to be a React component.");var s=[],h=void 0,m=function(e){function t(){return r(this,t),a(this,e.apply(this,arguments))}return u(t,e),t.peek=function(){return h},t.rewind=function(){if(t.canUseDOM)throw new Error("You may only call rewind() on the server. Call peek() to read the current state.");var e=h;return h=void 0,s=[],e},t.prototype.shouldComponentUpdate=function(e){return!(0,p.default)(e,this.props)},t.prototype.componentWillMount=function(){s.push(this),d()},t.prototype.componentDidUpdate=function(){d()},t.prototype.componentWillUnmount=function(){var e=s.indexOf(this);s.splice(e,1),d()},t.prototype.render=function(){return l.default.createElement(c,this.props)},t}(i.Component);return m.displayName="SideEffect("+o(c)+")",m.canUseDOM=f.default.canUseDOM,m}}},1356:function(e,t,n){var o;!function(){"use strict";var r=!("undefined"==typeof window||!window.document||!window.document.createElement),a={canUseDOM:r,canUseWorkers:"undefined"!=typeof Worker,canUseEventListeners:r&&!(!window.addEventListener&&!window.attachEvent),canUseViewport:r&&!!window.screen};void 0!==(o=function(){return a}.call(t,n,t,e))&&(e.exports=o)}()},1357:function(e,t,n){"use strict";function o(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var r=n(5),a=o(r),u=n(94),i=o(u),l=n(1358),c=o(l);t.default=function(e){var t=e.className,n=e.links,o=e.copyright,r=(0,i.default)(c.default.globalFooter,t);return a.default.createElement("div",{className:r},n&&a.default.createElement("div",{className:c.default.links},n.map(function(e){return a.default.createElement("a",{key:e.title,target:e.blankTarget?"_blank":"_self",href:e.href},e.title)})),o&&a.default.createElement("div",{className:c.default.copyright},o))},e.exports=t.default},1358:function(e,t){e.exports={globalFooter:"globalFooter___3DBsQ",links:"links___6ev0g",copyright:"copyright___2RCkh"}},1577:function(e,t){e.exports={container:"container___1jwf2",top:"top___3rfhZ",header:"header___3f0PM",logo:"logo___hFTf3",title:"title___UnZ9u",desc:"desc___twboJ",footer:"footer___rk8I2"}},813:function(e,t,n){"use strict";function o(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(t,"__esModule",{value:!0});var r,a,u=n(312),i=o(u),l=n(42),c=o(l),f=n(43),d=o(f),p=n(47),s=o(p),h=n(48),m=o(h),y=n(311),_=o(y);n(965);var v=n(5),E=o(v),w=n(8),b=o(w),g=n(313),k=n(1354),O=o(k),x=n(1357),C=o(x),j=n(1577),M=o(j),N=E.default.createElement("div",null,"Copyright ",E.default.createElement(_.default,{type:"copyright"})," 2017 \u559c\u591a\u9f99\u5a92\u4f53\u5e73\u53f0"),U=(a=r=function(e){function t(){return(0,c.default)(this,t),(0,s.default)(this,(t.__proto__||(0,i.default)(t)).apply(this,arguments))}return(0,m.default)(t,e),(0,d.default)(t,[{key:"getChildContext",value:function(){return{location:this.props.location}}},{key:"render",value:function(){var e=this.props.getRouteData;return E.default.createElement(O.default,{title:"\u559c\u591a\u9f99\u5a92\u4f53\u5e73\u53f0"},E.default.createElement("div",{className:M.default.container},E.default.createElement("div",{className:M.default.top},E.default.createElement("div",{className:M.default.header},E.default.createElement("div",{className:M.default.logo},E.default.createElement(g.Link,{to:"/"},E.default.createElement("span",{className:M.default.title},"\u559c\u591a\u9f99"))),E.default.createElement("ul",null,E.default.createElement("li",null,E.default.createElement(g.Link,{to:"/user/login"},"\u767b\u5f55")),E.default.createElement("li",null,E.default.createElement(g.Link,{to:"/user/register"},"\u6ce8\u518c"))))),e("PageLayout").map(function(e){return E.default.createElement(g.Route,{exact:e.exact,key:e.path,path:e.path,component:e.component})}),E.default.createElement(C.default,{className:M.default.footer,copyright:N})))}}]),t}(E.default.PureComponent),r.childContextTypes={location:b.default.object},a);t.default=U,e.exports=t.default},837:function(e,t){e.exports=function(e,t,n,o){var r=n?n.call(o,e,t):void 0;if(void 0!==r)return!!r;if(e===t)return!0;if("object"!=typeof e||!e||"object"!=typeof t||!t)return!1;var a=Object.keys(e),u=Object.keys(t);if(a.length!==u.length)return!1;for(var i=Object.prototype.hasOwnProperty.bind(t),l=0;l<a.length;l++){var c=a[l];if(!i(c))return!1;var f=e[c],d=t[c];if(!1===(r=n?n.call(o,f,d,c):void 0)||void 0===r&&f!==d)return!1}return!0}},965:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=n(192);n.n(o)}});