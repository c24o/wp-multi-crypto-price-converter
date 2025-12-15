/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@codeamp/block-components/dist/index.js":
/*!**************************************************************!*\
  !*** ./node_modules/@codeamp/block-components/dist/index.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, exports) => {

/*! For license information please see index.js.LICENSE.txt */
(()=>{var e={56:(e,t,o)=>{"use strict";e.exports=function(e){var t=o.nc;t&&e.setAttribute("nonce",t)}},72:e=>{"use strict";var t=[];function o(e){for(var o=-1,n=0;n<t.length;n++)if(t[n].identifier===e){o=n;break}return o}function n(e,n){for(var i={},c=[],l=0;l<e.length;l++){var a=e[l],s=n.base?a[0]+n.base:a[0],u=i[s]||0,p="".concat(s," ").concat(u);i[s]=u+1;var d=o(p),m={css:a[1],media:a[2],sourceMap:a[3],supports:a[4],layer:a[5]};if(-1!==d)t[d].references++,t[d].updater(m);else{var f=r(m,n);n.byIndex=l,t.splice(l,0,{identifier:p,updater:f,references:1})}c.push(p)}return c}function r(e,t){var o=t.domAPI(t);return o.update(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap&&t.supports===e.supports&&t.layer===e.layer)return;o.update(e=t)}else o.remove()}}e.exports=function(e,r){var i=n(e=e||[],r=r||{});return function(e){e=e||[];for(var c=0;c<i.length;c++){var l=o(i[c]);t[l].references--}for(var a=n(e,r),s=0;s<i.length;s++){var u=o(i[s]);0===t[u].references&&(t[u].updater(),t.splice(u,1))}i=a}}},113:e=>{"use strict";e.exports=function(e,t){if(t.styleSheet)t.styleSheet.cssText=e;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(e))}}},192:(e,t,o)=>{"use strict";o.d(t,{A:()=>l});var n=o(601),r=o.n(n),i=o(314),c=o.n(i)()(r());c.push([e.id,':root{--wp-admin-theme-color: #007cba;--wp-admin-theme-color--rgb: 0, 124, 186;--wp-admin-theme-color-darker-10: rgb(0, 107, 160.5);--wp-admin-theme-color-darker-10--rgb: 0, 107, 161;--wp-admin-theme-color-darker-20: #005a87;--wp-admin-theme-color-darker-20--rgb: 0, 90, 135;--wp-admin-border-width-focus: 2px;--wp-block-synced-color: #7a00df;--wp-block-synced-color--rgb: 122, 0, 223;--wp-bound-block-color: var(--wp-block-synced-color)}@media(-webkit-min-device-pixel-ratio: 2),(min-resolution: 192dpi){:root{--wp-admin-border-width-focus: 1.5px}}.codeamp-components-multi-select-control__input-container{font-size:13px;line-height:normal}.codeamp-components-multi-select-control__input-container{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;padding:6px 8px;box-shadow:0 0 0 rgba(0,0,0,0);border-radius:2px;border:1px solid #949494;font-size:16px;line-height:normal;width:100%;margin:0 0 8px 0;padding:0;cursor:text}@media not (prefers-reduced-motion){.codeamp-components-multi-select-control__input-container{transition:box-shadow .1s linear}}@media(min-width: 600px){.codeamp-components-multi-select-control__input-container{font-size:13px;line-height:normal}}.codeamp-components-multi-select-control__input-container:focus{border-color:var(--wp-admin-theme-color);box-shadow:0 0 0 .5px var(--wp-admin-theme-color);outline:2px solid rgba(0,0,0,0)}.codeamp-components-multi-select-control__input-container::-webkit-input-placeholder{color:rgba(30,30,30,.62)}.codeamp-components-multi-select-control__input-container::-moz-placeholder{color:rgba(30,30,30,.62)}.codeamp-components-multi-select-control__input-container:-ms-input-placeholder{color:rgba(30,30,30,.62)}.codeamp-components-multi-select-control__input-container.is-disabled{background:#ddd;border-color:#ddd}.codeamp-components-multi-select-control__input-container.is-active{border-color:var(--wp-admin-theme-color);box-shadow:0 0 0 .5px var(--wp-admin-theme-color);outline:2px solid rgba(0,0,0,0)}.codeamp-components-multi-select-control__input-container input[type=text].codeamp-components-multi-select-control__input{display:inline-block;flex:1;font-family:inherit;font-size:16px;width:100%;max-width:100%;margin-left:4px;padding:0;min-height:24px;min-width:50px;background:inherit;border:0;color:#1e1e1e;box-shadow:none;line-height:30px}@media(min-width: 600px){.codeamp-components-multi-select-control__input-container input[type=text].codeamp-components-multi-select-control__input{font-size:13px}}.codeamp-components-multi-select-control__input-container input[type=text].codeamp-components-multi-select-control__input:focus,.codeamp-components-multi-select-control.is-active .codeamp-components-multi-select-control__input-container input[type=text].codeamp-components-multi-select-control__input{outline:none;box-shadow:none}.codeamp-components-multi-select-control__input-container .codeamp-components-multi-select-control__token+input[type=text].codeamp-components-multi-select-control__input{width:auto}.codeamp-components-multi-select-control__help{font-size:12px;font-style:normal;color:#757575}.codeamp-components-multi-select-control__tokens-container{min-height:38px;padding:4px;width:100%}.codeamp-components-multi-select-control__token{font-size:13px;display:flex;color:#1e1e1e;max-width:100%;padding:0}.codeamp-components-multi-select-control__token.is-borderless{position:relative;padding:0 24px 0 0}.codeamp-components-multi-select-control__token.is-borderless .codeamp-components-multi-select-control__token-text{background:rgba(0,0,0,0);color:var(--wp-admin-theme-color)}.codeamp-components-multi-select-control__token.is-borderless .codeamp-components-multi-select-control__remove-token{background:rgba(0,0,0,0);color:#757575;position:absolute;top:1px;right:0;padding:0}.codeamp-components-multi-select-control__token.is-borderless.is-success .codeamp-components-multi-select-control__token-text{color:#4ab866}.codeamp-components-multi-select-control__token.is-borderless.is-error .codeamp-components-multi-select-control__token-text{color:#cc1818;border-radius:4px 0 0 4px;padding:0 4px 0 6px}.codeamp-components-multi-select-control__token.is-borderless.is-validating .codeamp-components-multi-select-control__token-text{color:#1e1e1e}.codeamp-components-multi-select-control__token.is-disabled .codeamp-components-multi-select-control__remove-token{cursor:default}.codeamp-components-multi-select-control__token-text,.codeamp-components-multi-select-control__remove-token.components-button{display:inline-block;line-height:30px;height:auto;background:#ddd;min-width:unset;transition:all .2s cubic-bezier(0.4, 1, 0.4, 1)}@media(prefers-reduced-motion: reduce){.codeamp-components-multi-select-control__token-text,.codeamp-components-multi-select-control__remove-token.components-button{transition-duration:0s;transition-delay:0s;animation-duration:1ms;animation-delay:0s}}.codeamp-components-multi-select-control__token-text{border-radius:2px 0 0 2px;padding:0 0 0 12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.codeamp-components-multi-select-control__remove-token.components-button{cursor:pointer;border-radius:0 2px 2px 0;padding:0 4px;color:#1e1e1e;line-height:10px;overflow:initial}.codeamp-components-multi-select-control__remove-token.components-button:hover{color:#1e1e1e}.codeamp-components-multi-select-control__suggestions-list{flex:1 0 100%;min-width:100%;overflow-y:auto;transition:all .15s ease-in-out;list-style:none;border-top:1px solid #757575;margin:0;padding:0}@media(prefers-reduced-motion: reduce){.codeamp-components-multi-select-control__suggestions-list{transition-duration:0s;transition-delay:0s}}.codeamp-components-multi-select-control__no-suggestions{color:#757575;font-size:13px;margin:0;display:block;padding:4px 8px}.codeamp-components-multi-select-control__suggestion{color:#757575;display:block;font-size:13px;padding:4px 8px;margin:0;cursor:pointer}.codeamp-components-multi-select-control__suggestion.is-selected{background:var(--wp-admin-theme-color);color:#fff}.codeamp-components-multi-select-control__suggestion-match{text-decoration:underline}',""]);const l=c},243:(e,t,o)=>{"use strict";o.d(t,{A:()=>l});var n=o(601),r=o.n(n),i=o(314),c=o.n(i)()(r());c.push([e.id,".codeamp-components-resource-select-control>.components-base-control__field{position:relative;display:flex;flex-wrap:wrap}.codeamp-components-resource-select-control>.components-base-control__field>.components-base-control__label{flex:2}.codeamp-components-resource-select-control__label{margin-bottom:8px}.codeamp-components-resource-select-control .codeamp-components-resource-select-control__menu_button.has-icon{height:40px;margin-bottom:0;min-width:26px;padding:2px 0;flex-basis:26px;width:26px}.codeamp-components-resource-select-control__select{width:auto;flex:1}.codeamp-components-resource-select-control .components-base-control{margin-bottom:0}.codeamp-components-resource-select-control .components-base-control__field{margin-bottom:0}.codeamp-components-resource-select-control .components-base-control{flex:1}",""]);const l=c},314:e=>{"use strict";e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var o="",n=void 0!==t[5];return t[4]&&(o+="@supports (".concat(t[4],") {")),t[2]&&(o+="@media ".concat(t[2]," {")),n&&(o+="@layer".concat(t[5].length>0?" ".concat(t[5]):""," {")),o+=e(t),n&&(o+="}"),t[2]&&(o+="}"),t[4]&&(o+="}"),o})).join("")},t.i=function(e,o,n,r,i){"string"==typeof e&&(e=[[null,e,void 0]]);var c={};if(n)for(var l=0;l<this.length;l++){var a=this[l][0];null!=a&&(c[a]=!0)}for(var s=0;s<e.length;s++){var u=[].concat(e[s]);n&&c[u[0]]||(void 0!==i&&(void 0===u[5]||(u[1]="@layer".concat(u[5].length>0?" ".concat(u[5]):""," {").concat(u[1],"}")),u[5]=i),o&&(u[2]?(u[1]="@media ".concat(u[2]," {").concat(u[1],"}"),u[2]=o):u[2]=o),r&&(u[4]?(u[1]="@supports (".concat(u[4],") {").concat(u[1],"}"),u[4]=r):u[4]="".concat(r)),t.push(u))}},t}},485:(e,t)=>{var o;!function(){"use strict";var n={}.hasOwnProperty;function r(){for(var e=[],t=0;t<arguments.length;t++){var o=arguments[t];if(o){var i=typeof o;if("string"===i||"number"===i)e.push(o);else if(Array.isArray(o)){if(o.length){var c=r.apply(null,o);c&&e.push(c)}}else if("object"===i){if(o.toString!==Object.prototype.toString&&!o.toString.toString().includes("[native code]")){e.push(o.toString());continue}for(var l in o)n.call(o,l)&&o[l]&&e.push(l)}}}return e.join(" ")}e.exports?(r.default=r,e.exports=r):void 0===(o=function(){return r}.apply(t,[]))||(e.exports=o)}()},540:e=>{"use strict";e.exports=function(e){var t=document.createElement("style");return e.setAttributes(t,e.attributes),e.insert(t,e.options),t}},601:e=>{"use strict";e.exports=function(e){return e[1]}},659:e=>{"use strict";var t={};e.exports=function(e,o){var n=function(e){if(void 0===t[e]){var o=document.querySelector(e);if(window.HTMLIFrameElement&&o instanceof window.HTMLIFrameElement)try{o=o.contentDocument.head}catch(e){o=null}t[e]=o}return t[e]}(e);if(!n)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");n.appendChild(o)}},825:e=>{"use strict";e.exports=function(e){var t=e.insertStyleElement(e);return{update:function(o){!function(e,t,o){var n="";o.supports&&(n+="@supports (".concat(o.supports,") {")),o.media&&(n+="@media ".concat(o.media," {"));var r=void 0!==o.layer;r&&(n+="@layer".concat(o.layer.length>0?" ".concat(o.layer):""," {")),n+=o.css,r&&(n+="}"),o.media&&(n+="}"),o.supports&&(n+="}");var i=o.sourceMap;i&&"undefined"!=typeof btoa&&(n+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(i))))," */")),t.styleTagTransform(n,e,t.options)}(t,e,o)},remove:function(){!function(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e)}(t)}}}}},t={};function o(n){var r=t[n];if(void 0!==r)return r.exports;var i=t[n]={id:n,exports:{}};return e[n](i,i.exports,o),i.exports}o.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return o.d(t,{a:t}),t},o.d=(e,t)=>{for(var n in t)o.o(t,n)&&!o.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),o.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.nc=void 0;var n={};(()=>{"use strict";o.r(n),o.d(n,{MultiSelectControl:()=>Pe,ResourceSelectControl:()=>C});const e=window.wp.i18n,t=window.wp.components,r=window.wp.compose,i=window.React,c=window.wp.primitives,l=(0,i.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,i.createElement)(c.Path,{d:"M13 19h-2v-2h2v2zm0-6h-2v-2h2v2zm0-6h-2V5h2v2z"}));var a=o(72),s=o.n(a),u=o(825),p=o.n(u),d=o(659),m=o.n(d),f=o(56),v=o.n(f),b=o(540),g=o.n(b),h=o(113),y=o.n(h),w=o(243),_={};_.styleTagTransform=y(),_.setAttributes=v(),_.insert=m().bind(null,"head"),_.domAPI=p(),_.insertStyleElement=g(),s()(w.A,_),w.A&&w.A.locals&&w.A.locals;var x=o(485),S=o.n(x);function O(e){return O="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},O(e)}function k(){return k=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var o=arguments[t];for(var n in o)({}).hasOwnProperty.call(o,n)&&(e[n]=o[n])}return e},k.apply(null,arguments)}function j(e,t){var o=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),o.push.apply(o,n)}return o}function E(e){for(var t=1;t<arguments.length;t++){var o=null!=arguments[t]?arguments[t]:{};t%2?j(Object(o),!0).forEach((function(t){P(e,t,o[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(o)):j(Object(o)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(o,t))}))}return e}function P(e,t,o){return(t=function(e){var t=function(e){if("object"!=O(e)||!e)return e;var t=e[Symbol.toPrimitive];if(void 0!==t){var o=t.call(e,"string");if("object"!=O(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(e)}(e);return"symbol"==O(t)?t:t+""}(t))in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e}function A(e,t){(null==t||t>e.length)&&(t=e.length);for(var o=0,n=Array(t);o<t;o++)n[o]=e[o];return n}var I=function(){},C=function(o){var n,i,c=o.onChange,a=void 0===c?I:c,s=o.label,u=void 0===s?"":s,p=o.loadingLabel,d=void 0===p?(0,e.__)("Loading","codeamp-block-components"):p,m=o.showActions,f=void 0===m||m,v=o.dropdownProps,b=o.dropdownToggleProps,g=o.disabled,h=void 0!==g&&g,y=o.defaultOption,w=o.options,_=o.value,x=o.help,O=o.id,j=o.className,P=[];d&&(P=[{value:"loading",label:d}]),w&&(P=[],y&&P.push(y),(n=P).push.apply(n,function(e){if(Array.isArray(e))return A(e)}(i=w)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(i)||function(e,t){if(e){if("string"==typeof e)return A(e,t);var o={}.toString.call(e).slice(8,-1);return"Object"===o&&e.constructor&&(o=e.constructor.name),"Map"===o||"Set"===o?Array.from(e):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?A(e,t):void 0}}(i)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()));var M=(0,r.useInstanceId)(C,"codeamp-components-resource-select-control");return O&&(M=O),React.createElement(t.BaseControl,{id:M,className:S()("components-base-control codeamp-components-resource-select-control",j),help:x,label:u,__nextHasNoMarginBottom:!0},React.createElement(t.__experimentalHStack,null,React.createElement(t.SelectControl,{id:M,value:_,options:P,className:"codeamp-components-resource-select-control__select",onChange:a,disabled:h,__nextHasNoMarginBottom:!0,__next40pxDefaultSize:!0}),f&&React.createElement(t.DropdownMenu,k({icon:l,toggleProps:E(E({className:"codeamp-components-resource-select-control__menu_button",iconSize:26},b),{},{__next40pxDefaultSize:!0})},v))))};const M=window.wp.element,T=window.wp.a11y,L=window.wp.isShallowEqual;var R=o.n(L);const D=(0,i.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,i.createElement)(c.Path,{d:"M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"}));var N=function(){};function z(o){var n=o.value,i=o.label,c=o.title,l=o.isBorderless,a=void 0!==l&&l,s=o.disabled,u=void 0!==s&&s,p=o.onClickRemove,d=void 0===p?N:p,m=o.onMouseEnter,f=o.onMouseLeave,v=o.messages,b=o.termPosition,g=o.termsCount,h=(0,r.useInstanceId)(z),y=S()("codeamp-components-multi-select-control__token",{"is-borderless":a,"is-disabled":u}),w=(0,e.sprintf)((0,e.__)("%1$s (%2$s of %3$s)"),i,b,g);return React.createElement("span",{className:y,onMouseEnter:m,onMouseLeave:f,title:c,style:{margin:"0"}},React.createElement("span",{className:"codeamp-components-multi-select-control__token-text",id:"codeamp-components-multi-select-control__token-text-".concat(h)},React.createElement(t.VisuallyHidden,{as:"span"},w),React.createElement("span",{"aria-hidden":"true"},i)),React.createElement(t.Button,{className:"codeamp-components-multi-select-control__remove-token",icon:D,onClick:u?N:function(){return d({value:n})},label:v.remove,"aria-describedby":"codeamp-components-multi-select-control__token-text-".concat(h)}))}var B=["value","isExpanded","instanceId","selectedSuggestionIndex","className","onChange","onFocus","onBlur"];function W(){return W=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var o=arguments[t];for(var n in o)({}).hasOwnProperty.call(o,n)&&(e[n]=o[n])}return e},W.apply(null,arguments)}function F(e,t){(null==t||t>e.length)&&(t=e.length);for(var o=0,n=Array(t);o<t;o++)n[o]=e[o];return n}const H=(0,M.forwardRef)((function(e,t){var o,n,r=e.value,i=e.isExpanded,c=e.instanceId,l=e.selectedSuggestionIndex,a=e.className,s=e.onChange,u=e.onFocus,p=e.onBlur,d=function(e,t){if(null==e)return{};var o,n,r=function(e,t){if(null==e)return{};var o={};for(var n in e)if({}.hasOwnProperty.call(e,n)){if(-1!==t.indexOf(n))continue;o[n]=e[n]}return o}(e,t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);for(n=0;n<i.length;n++)o=i[n],-1===t.indexOf(o)&&{}.propertyIsEnumerable.call(e,o)&&(r[o]=e[o])}return r}(e,B),m=(o=(0,M.useState)(!1),n=2,function(e){if(Array.isArray(e))return e}(o)||function(e,t){var o=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=o){var n,r,i,c,l=[],a=!0,s=!1;try{if(i=(o=o.call(e)).next,0===t){if(Object(o)!==o)return;a=!1}else for(;!(a=(n=i.call(o)).done)&&(l.push(n.value),l.length!==t);a=!0);}catch(e){s=!0,r=e}finally{try{if(!a&&null!=o.return&&(c=o.return(),Object(c)!==c))return}finally{if(s)throw r}}return l}}(o,n)||function(e,t){if(e){if("string"==typeof e)return F(e,t);var o={}.toString.call(e).slice(8,-1);return"Object"===o&&e.constructor&&(o=e.constructor.name),"Map"===o||"Set"===o?Array.from(e):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?F(e,t):void 0}}(o,n)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()),f=m[0],v=m[1],b=r?r.length+1:0;return React.createElement("input",W({ref:t,id:c,type:"text"},d,{value:r||"",onChange:function(e){s&&s({value:e.target.value})},onFocus:function(e){v(!0),null==u||u(e)},onBlur:function(e){v(!1),null==p||p(e)},size:b,className:S()(a,"codeamp-components-multi-select-control__input"),autoComplete:"off",role:"combobox","aria-expanded":i,"aria-autocomplete":"list","aria-owns":i?"".concat(c,"-suggestions"):void 0,"aria-activedescendant":f&&-1!==l&&i?"".concat(c,"-suggestions-").concat(l):void 0,"aria-describedby":"".concat(c,"-howto"),"data-lpignore":"true"}))}));function V(e){return V="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},V(e)}function U(e,t,o){return t in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e}function $(e,t){var o=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),o.push.apply(o,n)}return o}function q(e,t){var o=e["page".concat(t?"Y":"X","Offset")],n="scroll".concat(t?"Top":"Left");if("number"!=typeof o){var r=e.document;"number"!=typeof(o=r.documentElement[n])&&(o=r.body[n])}return o}function G(e){return q(e)}function K(e){return q(e,!0)}function J(e){var t=function(e){var t,o,n,r=e.ownerDocument,i=r.body,c=r&&r.documentElement;return o=(t=e.getBoundingClientRect()).left,n=t.top,{left:o-=c.clientLeft||i.clientLeft||0,top:n-=c.clientTop||i.clientTop||0}}(e),o=e.ownerDocument,n=o.defaultView||o.parentWindow;return t.left+=G(n),t.top+=K(n),t}var X,Y=new RegExp("^(".concat(/[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source,")(?!px)[a-z%]+$"),"i"),Q=/^(top|right|bottom|left)$/,Z="currentStyle",ee="runtimeStyle",te="left";function oe(e,t){for(var o=0;o<e.length;o++)t(e[o])}function ne(e){return"border-box"===X(e,"boxSizing")}"undefined"!=typeof window&&(X=window.getComputedStyle?function(e,t,o){var n="",r=e.ownerDocument,i=o||r.defaultView.getComputedStyle(e,null);return i&&(n=i.getPropertyValue(t)||i[t]),n}:function(e,t){var o=e[Z]&&e[Z][t];if(Y.test(o)&&!Q.test(t)){var n=e.style,r=n[te],i=e[ee][te];e[ee][te]=e[Z][te],n[te]="fontSize"===t?"1em":o||0,o=n.pixelLeft+"px",n[te]=r,e[ee][te]=i}return""===o?"auto":o});var re=["margin","border","padding"];function ie(e,t,o){var n,r,i,c=0;for(r=0;r<t.length;r++)if(n=t[r])for(i=0;i<o.length;i++){var l;l="border"===n?"".concat(n+o[i],"Width"):n+o[i],c+=parseFloat(X(e,l))||0}return c}function ce(e){return null!=e&&e==e.window}var le={};function ae(e,t,o){if(ce(e))return"width"===t?le.viewportWidth(e):le.viewportHeight(e);if(9===e.nodeType)return"width"===t?le.docWidth(e):le.docHeight(e);var n="width"===t?["Left","Right"]:["Top","Bottom"],r="width"===t?e.offsetWidth:e.offsetHeight,i=(X(e),ne(e)),c=0;(null==r||r<=0)&&(r=void 0,(null==(c=X(e,t))||Number(c)<0)&&(c=e.style[t]||0),c=parseFloat(c)||0),void 0===o&&(o=i?1:-1);var l=void 0!==r||i,a=r||c;if(-1===o)return l?a-ie(e,["border","padding"],n):c;if(l){var s=2===o?-ie(e,["border"],n):ie(e,["margin"],n);return a+(1===o?0:s)}return c+ie(e,re.slice(o),n)}oe(["Width","Height"],(function(e){le["doc".concat(e)]=function(t){var o=t.document;return Math.max(o.documentElement["scroll".concat(e)],o.body["scroll".concat(e)],le["viewport".concat(e)](o))},le["viewport".concat(e)]=function(t){var o="client".concat(e),n=t.document,r=n.body,i=n.documentElement[o];return"CSS1Compat"===n.compatMode&&i||r&&r[o]||i}}));var se={position:"absolute",visibility:"hidden",display:"block"};function ue(e){var t,o=arguments;return 0!==e.offsetWidth?t=ae.apply(void 0,o):function(e,n){var r,i={},c=e.style;for(r in n)n.hasOwnProperty(r)&&(i[r]=c[r],c[r]=n[r]);for(r in function(){t=ae.apply(void 0,o)}.call(e),n)n.hasOwnProperty(r)&&(c[r]=i[r])}(e,se),t}function pe(e,t,o){var n=o;if("object"!==V(t))return void 0!==n?("number"==typeof n&&(n+="px"),void(e.style[t]=n)):X(e,t);for(var r in t)t.hasOwnProperty(r)&&pe(e,r,t[r])}oe(["width","height"],(function(e){var t=e.charAt(0).toUpperCase()+e.slice(1);le["outer".concat(t)]=function(t,o){return t&&ue(t,e,o?0:1)};var o="width"===e?["Left","Right"]:["Top","Bottom"];le[e]=function(t,n){return void 0===n?t&&ue(t,e,-1):t?(X(t),ne(t)&&(n+=ie(t,["padding","border"],o)),pe(t,e,n)):void 0}}));var de=function(e){for(var t=1;t<arguments.length;t++){var o=null!=arguments[t]?arguments[t]:{};t%2?$(o,!0).forEach((function(t){U(e,t,o[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(o)):$(o).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(o,t))}))}return e}({getWindow:function(e){var t=e.ownerDocument||e;return t.defaultView||t.parentWindow},offset:function(e,t){if(void 0===t)return J(e);!function(e,t){"static"===pe(e,"position")&&(e.style.position="relative");var o,n,r=J(e),i={};for(n in t)t.hasOwnProperty(n)&&(o=parseFloat(pe(e,n))||0,i[n]=o+t[n]-r[n]);pe(e,i)}(e,t)},isWindow:ce,each:oe,css:pe,clone:function(e){var t={};for(var o in e)e.hasOwnProperty(o)&&(t[o]=e[o]);if(e.overflow)for(var n in e)e.hasOwnProperty(n)&&(t.overflow[n]=e.overflow[n]);return t},scrollLeft:function(e,t){if(ce(e)){if(void 0===t)return G(e);window.scrollTo(t,K(e))}else{if(void 0===t)return e.scrollLeft;e.scrollLeft=t}},scrollTop:function(e,t){if(ce(e)){if(void 0===t)return K(e);window.scrollTo(G(e),t)}else{if(void 0===t)return e.scrollTop;e.scrollTop=t}},viewportWidth:0,viewportHeight:0},le);function me(e,t){(null==t||t>e.length)&&(t=e.length);for(var o=0,n=Array(t);o<t;o++)n[o]=e[o];return n}var fe=function(e){e.preventDefault()};const ve=function(t){var o,n,i=t.selectedIndex,c=t.scrollIntoView,l=t.searchValue,a=t.onHover,s=t.onSelect,u=t.suggestions,p=void 0===u?[]:u,d=t.instanceId,m=t.__experimentalRenderItem,f=(o=(0,M.useState)(!1),n=2,function(e){if(Array.isArray(e))return e}(o)||function(e,t){var o=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=o){var n,r,i,c,l=[],a=!0,s=!1;try{if(i=(o=o.call(e)).next,0===t){if(Object(o)!==o)return;a=!1}else for(;!(a=(n=i.call(o)).done)&&(l.push(n.value),l.length!==t);a=!0);}catch(e){s=!0,r=e}finally{try{if(!a&&null!=o.return&&(c=o.return(),Object(c)!==c))return}finally{if(s)throw r}}return l}}(o,n)||function(e,t){if(e){if("string"==typeof e)return me(e,t);var o={}.toString.call(e).slice(8,-1);return"Object"===o&&e.constructor&&(o=e.constructor.name),"Map"===o||"Set"===o?Array.from(e):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?me(e,t):void 0}}(o,n)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()),v=f[0],b=f[1],g=(0,r.useRefEffect)((function(e){var t;return i>-1&&c&&e.children[i]&&(b(!0),function(e,t,o){o=o||{},9===t.nodeType&&(t=de.getWindow(t));var n=o.allowHorizontalScroll,r=o.onlyScrollIfNeeded,i=o.alignWithTop,c=o.alignWithLeft,l=o.offsetTop||0,a=o.offsetLeft||0,s=o.offsetBottom||0,u=o.offsetRight||0;n=void 0===n||n;var p,d,m,f,v,b,g,h,y,w,_=de.isWindow(t),x=de.offset(e),S=de.outerHeight(e),O=de.outerWidth(e);_?(g=t,w=de.height(g),y=de.width(g),h={left:de.scrollLeft(g),top:de.scrollTop(g)},v={left:x.left-h.left-a,top:x.top-h.top-l},b={left:x.left+O-(h.left+y)+u,top:x.top+S-(h.top+w)+s},f=h):(p=de.offset(t),d=t.clientHeight,m=t.clientWidth,f={left:t.scrollLeft,top:t.scrollTop},v={left:x.left-(p.left+(parseFloat(de.css(t,"borderLeftWidth"))||0))-a,top:x.top-(p.top+(parseFloat(de.css(t,"borderTopWidth"))||0))-l},b={left:x.left+O-(p.left+m+(parseFloat(de.css(t,"borderRightWidth"))||0))+u,top:x.top+S-(p.top+d+(parseFloat(de.css(t,"borderBottomWidth"))||0))+s}),v.top<0||b.top>0?!0===i?de.scrollTop(t,f.top+v.top):!1===i?de.scrollTop(t,f.top+b.top):v.top<0?de.scrollTop(t,f.top+v.top):de.scrollTop(t,f.top+b.top):r||((i=void 0===i||!!i)?de.scrollTop(t,f.top+v.top):de.scrollTop(t,f.top+b.top)),n&&(v.left<0||b.left>0?!0===c?de.scrollLeft(t,f.left+v.left):!1===c?de.scrollLeft(t,f.left+b.left):v.left<0?de.scrollLeft(t,f.left+v.left):de.scrollLeft(t,f.left+b.left):r||((c=void 0===c||!!c)?de.scrollLeft(t,f.left+v.left):de.scrollLeft(t,f.left+b.left)))}(e.children[i],e,{onlyScrollIfNeeded:!0}),t=requestAnimationFrame((function(){b(!1)}))),function(){void 0!==t&&cancelAnimationFrame(t)}}),[i,c]),h=function(e){return function(){v||null==a||a(e)}},y=function(e){return function(){null==s||s(e)}};return React.createElement("ul",{ref:g,className:"codeamp-components-multi-select-control__suggestions-list",id:"".concat(d,"-suggestions"),role:"listbox"},0===p.length&&React.createElement("li",{className:"codeamp-components-multi-select-control__no-suggestions",role:"option"},(0,e.__)("No results found.","codeamp-block-components")),p.map((function(e,t){var o,n=function(e){var t=e.label.toLocaleLowerCase().indexOf(l);return{suggestionBeforeMatch:e.label.substring(0,t),suggestionMatch:e.label.substring(t,t+l.length),suggestionAfterMatch:e.label.substring(t+l.length)}}(e),r=S()("codeamp-components-multi-select-control__suggestion",{"is-selected":t===i});return o="function"==typeof m?m({item:e}):n?React.createElement("span",{"aria-label":e.label},n.suggestionBeforeMatch,React.createElement("strong",{className:"codeamp-components-multi-select-control__suggestion-match"},n.suggestionMatch),n.suggestionAfterMatch):e.label,React.createElement("li",{id:"".concat(d,"-suggestions-").concat(t),role:"option",className:r,key:e.value,onMouseDown:fe,onClick:y(e),onMouseEnter:h(e),"aria-selected":t===i},o)})))};var be=o(192),ge={};function he(){return he=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var o=arguments[t];for(var n in o)({}).hasOwnProperty.call(o,n)&&(e[n]=o[n])}return e},he.apply(null,arguments)}function ye(e,t){var o=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),o.push.apply(o,n)}return o}function we(e,t,o){return(t=function(e){var t=function(e){if("object"!=_e(e)||!e)return e;var t=e[Symbol.toPrimitive];if(void 0!==t){var o=t.call(e,"string");if("object"!=_e(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(e)}(e);return"symbol"==_e(t)?t:t+""}(t))in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e}function _e(e){return _e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},_e(e)}function xe(e){return function(e){if(Array.isArray(e))return ke(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||Oe(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function Se(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var o=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=o){var n,r,i,c,l=[],a=!0,s=!1;try{if(i=(o=o.call(e)).next,0===t){if(Object(o)!==o)return;a=!1}else for(;!(a=(n=i.call(o)).done)&&(l.push(n.value),l.length!==t);a=!0);}catch(e){s=!0,r=e}finally{try{if(!a&&null!=o.return&&(c=o.return(),Object(c)!==c))return}finally{if(s)throw r}}return l}}(e,t)||Oe(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function Oe(e,t){if(e){if("string"==typeof e)return ke(e,t);var o={}.toString.call(e).slice(8,-1);return"Object"===o&&e.constructor&&(o=e.constructor.name),"Map"===o||"Set"===o?Array.from(e):"Arguments"===o||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(o)?ke(e,t):void 0}}function ke(e,t){(null==t||t>e.length)&&(t=e.length);for(var o=0,n=Array(t);o<t;o++)n[o]=e[o];return n}function je(e){if(""===e)return null;var t=null!=e?e:"";return(arguments.length>1&&void 0!==arguments[1]?arguments[1]:[]).find((function(e){return-1!==e.label.toLocaleLowerCase().indexOf(t.trim().toLocaleLowerCase())}))}ge.styleTagTransform=y(),ge.setAttributes=v(),ge.insert=m().bind(null,"head"),ge.domAPI=p(),ge.insertStyleElement=g(),s()(be.A,ge),be.A&&be.A.locals&&be.A.locals;var Ee=function(){};function Pe(o){var n=o.autoCapitalize,i=o.autoComplete,c=o.maxLength,l=o.placeholder,a=o.label,s=void 0===a?(0,e.__)("Add item"):a,u=o.help,p=o.className,d=o.suggestions,m=void 0===d?[]:d,f=o.options,v=void 0===f?[]:f,b=o.maxSuggestions,g=void 0===b?100:b,h=o.value,y=void 0===h?[]:h,w=o.onChange,_=void 0===w?function(){}:w,x=o.onInputChange,O=void 0===x?function(){}:x,k=o.onFocus,j=void 0===k?void 0:k,E=(o.isBorderless,o.id),P=o.disabled,A=void 0!==P&&P,I=o.messages,C=void 0===I?{added:(0,e.__)("Item added."),removed:(0,e.__)("Item removed."),remove:(0,e.__)("Remove item"),__experimentalInvalid:(0,e.__)("Invalid item")}:I,L=o.__experimentalRenderItem,D=o.__experimentalAutoSelectFirstMatch,N=void 0===D||D,B=o.__experimentalValidateInput,W=void 0===B?function(){return!0}:B,F=o.__experimentalCloseSuggestionsOnSelect,V=void 0===F||F,U=(0,r.useInstanceId)(Pe,"codeamp-components-multi-select-control");E&&(U=E);var $=Se((0,M.useState)(""),2),q=$[0],G=$[1],K=Se((0,M.useState)(0),2),J=K[0],X=K[1],Y=Se((0,M.useState)(!1),2),Q=Y[0],Z=Y[1],ee=Se((0,M.useState)(!1),2),te=ee[0],oe=ee[1],ne=Se((0,M.useState)(-1),2),re=ne[0],ie=ne[1],ce=Se((0,M.useState)(!1),2),le=ce[0],ae=ce[1],se=(0,r.usePrevious)(m),ue=(0,r.usePrevious)(y),pe=(0,M.useRef)(null),de=(0,M.useRef)(null),me=(0,r.useDebounce)(T.speak,500);function fe(){var e;null===(e=pe.current)||void 0===e||e.focus()}function be(){var e;return pe.current===(null===(e=pe.current)||void 0===e?void 0:e.ownerDocument.activeElement)}function ge(e){be()||e.target===de.current?(Z(!0),oe(!0)):Z(!1),"function"==typeof j&&j(e)}function Oe(e){e.target===de.current&&Q&&e.preventDefault()}function ke(e){Te(e.value),fe()}function Ae(e){var t=!1;return be()&&ze()&&(e(),t=!0),t}function Ie(){var e=Ne()-1;e>-1&&Te(y[e])}function Ce(){var e=Ne();e<y.length&&(Te(y[e]),function(e){X(y.length-Math.max(e,-1)-1)}(e))}function Me(e){W(e.label)?(function(e){if(xe(new Set(e.filter((function(e){return!function(e){return y.some((function(t){return Le(e)===Le(t)}))}(e)})))),e.length>0){var t=xe(y);t.splice.apply(t,[Ne(),0].concat(xe(e))),_(t)}}([e.value]),(0,T.speak)(C.added,"assertive"),G(""),ae(!1),ie(-1),V&&oe(!1),Q&&fe()):(0,T.speak)(C.__experimentalInvalid,"assertive")}function Te(e){var t=y.filter((function(t){return Le(t)!==Le(e)}));_(t),(0,T.speak)(C.removed,"assertive")}function Le(e){return"object"===_e(e)?e.value:e}function Re(){return v.filter((function(e){return-1===y.indexOf(e.value)}))}function De(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:q,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:Re(),o=arguments.length>3&&void 0!==arguments[3]?arguments[3]:g;if(""!==e.trim()){var n=[],r=[];t.forEach((function(t){var o=t.label.toLocaleLowerCase().indexOf(e.trim().toLocaleLowerCase());0===o?n.push(t):o>0&&r.push(t)})),t=n.concat(r)}return t.slice(0,o)}function Ne(){return y.length-J}function ze(){return 0===q.length}function Be(){var e;return(null===(e=je(q))||void 0===e||null===(e=e.label)||void 0===e?void 0:e.length)>0}function We(){var t=!(arguments.length>0&&void 0!==arguments[0])||arguments[0],o=De(q),n=o.length>0;t&&(N&&n?(ie(0),ae(!0)):(ie(-1),ae(!1))),ie(0);var r=n?(0,e.sprintf)((0,e._n)("%d result found, use up and down arrow keys to navigate.","%d results found, use up and down arrow keys to navigate.",o.length),o.length):(0,e.__)("No results.");me(r,"assertive")}function Fe(e){return v.find((function(t){return t.value===e}))||null}(0,M.useEffect)((function(){Q&&!be()&&fe()}),[Q]),(0,M.useEffect)((function(){var e=!R()(m,se||[]);(e||y!==ue)&&We(e)}),[m,se,y,ue]),(0,M.useEffect)((function(){We()}),[q]),A&&Q&&(Z(!1),G(""));var He,Ve,Ue=S()(p,"codeamp-components-multi-select-control__input-container",{"is-active":Q,"is-disabled":A}),$e={className:"components-base-control codeamp-components-multi-select-control",tabIndex:-1},qe=De();return A||($e=Object.assign({},$e,{onKeyDown:function(e){var t=!1;if(!e.defaultPrevented){switch(e.code){case"Backspace":t=Ae(Ie);break;case"Enter":case"Space":t=function(){var e=!1,t=function(){if(-1!==re)return De()[re]}();return t&&te?(Me(t),e=!0):Be()&&""!==q.trim()&&(Me(q),e=!0),e}();break;case"ArrowLeft":t=function(){var e=!1;return ze()&&(X((function(e){return Math.min(e+1,y.length)})),e=!0),e}();break;case"ArrowUp":t=te?(ie((function(e){return(0===e?De(q,Re(),y,g).length:e)-1})),ae(!0),!0):(oe(!0),ie(0),ae(!0),!0);break;case"ArrowRight":t=function(){var e=!1;return ze()&&(X((function(e){return Math.max(e-1,0)})),e=!0),e}();break;case"ArrowDown":t=te?(ie((function(e){return(e+1)%De(q,Re(),y,g).length})),ae(!0),!0):(oe(!0),ie(0),ae(!0),!0);break;case"Delete":t=Ae(Ce);break;case"Escape":t=function(e){return e.target instanceof HTMLInputElement&&(G(e.target.value),oe(!1),ie(-1),ae(!1)),!0}(e)}t&&e.preventDefault()}},onKeyPress:function(e){var t=!1;44===e.charCode&&(Be()&&Me(q),t=!0),t&&e.preventDefault()},onFocus:ge})),React.createElement(t.BaseControl,{id:U,label:s,help:u},React.createElement("div",$e,React.createElement("div",{ref:de,className:Ue,tabIndex:-1,onMouseDown:Oe,onTouchStart:Oe},React.createElement(t.Flex,{className:"codeamp-components-multi-select-control__tokens-container",justify:"flex-start",align:"flex-start",gap:"4px",wrap:!0,hasTokens:!!y.length},(Ve=[],y.forEach((function(e,o){var n=Fe(e);Fe(e)&&Ve.push(function(e,o){var n=e.value,r=e.label,i=e.onMouseEnter,c=void 0===i?Ee:i,l=e.onMouseLeave,a=void 0===l?Ee:l,s=e.isBorderless,u=void 0!==s&&s,p=n,d=o+1;return React.createElement(t.FlexItem,{key:"token-"+p},React.createElement(z,{value:p,label:r,title:"string"!=typeof token?r:void 0,onClickRemove:ke,isBorderless:u,onMouseEnter:c,onMouseLeave:a,disabled:A,messages:C,termPosition:d,termsCount:y.length}))}(function(e){for(var t=1;t<arguments.length;t++){var o=null!=arguments[t]?arguments[t]:{};t%2?ye(Object(o),!0).forEach((function(t){we(e,t,o[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(o)):ye(Object(o)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(o,t))}))}return e}({},n),o))})),Ve.splice(Ne(),0,(He={instanceId:U,autoCapitalize:n,autoComplete:i,placeholder:0===y.length?l:"",key:"input",disabled:A,value:q,onBlur:function(){Be()?Z(!1):(G(""),X(0),Z(!1),oe(!1),ie(-1),ae(!1))},isExpanded:te,selectedSuggestionIndex:re,onClick:ge},React.createElement(H,he({},He,{onChange:c&&y.length>=c?void 0:function(e){var t=e.value;G(t),oe(!0),O(t)},ref:pe})))),Ve)),te&&React.createElement(ve,{instanceId:U,match:je(q,v),searchValue:q.trim(),suggestions:qe,selectedIndex:re,scrollIntoView:le,onHover:function(e){var t=De().indexOf(e);t>=0&&(ie(t),ae(!1))},onSelect:function(e){Me(e)},__experimentalRenderItem:L}))))}})();var r=exports;for(var i in n)r[i]=n[i];n.__esModule&&Object.defineProperty(r,"__esModule",{value:!0})})();

/***/ }),

/***/ "./src/blocks/converter/block.json":
/*!*****************************************!*\
  !*** ./src/blocks/converter/block.json ***!
  \*****************************************/
/***/ ((module) => {

"use strict";
module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"multi-crypto-convert/converter","version":"0.1.0","title":"Multi Crypto Converter","category":"widgets","icon":"money-alt","description":"Widget to convert a value to multiple cryptocurrencies.","example":{},"supports":{"html":false},"textdomain":"multi-crypto-convert-wp-plugin","editorScript":"file:./index.js","style":"file:./style-index.css","viewScript":"file:./view.js","attributes":{"coins":{"type":"string"}}}');

/***/ }),

/***/ "./src/blocks/converter/edit.js":
/*!**************************************!*\
  !*** ./src/blocks/converter/edit.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Edit)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _codeamp_block_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @codeamp/block-components */ "./node_modules/@codeamp/block-components/dist/index.js");
/* harmony import */ var _codeamp_block_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_codeamp_block_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */


/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */





/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */

function Edit({
  attributes,
  setAttributes
}) {
  const [coinsList, setCoinsList] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)([]);
  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useState)(true);
  const coins = attributes.coins && 0 < attributes.coins.length ? attributes.coins.split(',') : [];

  // Fetch selected coins from the admin settings via REST API.
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.useEffect)(() => {
    const fetchSelectedAvailableCoins = async () => {
      try {
        const response = await fetch('/wp-json/mcc/v1/selected-available-coins');
        if (!response.ok) {
          throw new Error('Failed to fetch the selected available coins.');
        }
        const data = await response.json();
        if (data.success && data.data) {
          setCoinsList(data.data);
        } else {
          setCoinsList([]);
        }
      } catch (error) {
        console.error('Error fetching the selected available coins:', error);
        setCoinsList([]);
      } finally {
        setIsLoading(false);
      }
    };
    fetchSelectedAvailableCoins();
  }, []);
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Coins', 'multi-crypto-convert'),
        children: isLoading ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("p", {
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Loading coins...', 'multi-crypto-convert')
        }) : coinsList.length === 0 ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("p", {
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('No coins available. Please configure coins in the plugin settings.', 'multi-crypto-convert')
        }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_codeamp_block_components__WEBPACK_IMPORTED_MODULE_3__.MultiSelectControl, {
          __next40pxDefaultSize: true,
          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Select Coins', 'multi-crypto-convert'),
          value: coins,
          options: coinsList,
          onChange: selectedCoins => setAttributes({
            coins: selectedCoins.join(',')
          }),
          __nextHasNoMarginBottom: true
        })
      })
    }), coins && coins.length > 0 ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      ...(0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)(),
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Selected coins:', 'multi-crypto-convert')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("ul", {
        children: coins.map(coin => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("li", {
          children: coinsList.find(coinItem => coinItem.value === coin)?.label || coin
        }, coin))
      })]
    }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("p", {
      ...(0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)(),
      children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('No coins selected.', 'multi-crypto-convert')
    })]
  });
}

/***/ }),

/***/ "./src/blocks/converter/index.js":
/*!***************************************!*\
  !*** ./src/blocks/converter/index.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./src/blocks/converter/style.scss");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/blocks/converter/edit.js");
/* harmony import */ var _save__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./save */ "./src/blocks/converter/save.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./block.json */ "./src/blocks/converter/block.json");
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * Internal dependencies
 */




/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_4__.name, {
  /**
   * @see ./edit.js
   */
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"],
  /**
   * @see ./save.js
   */
  save: _save__WEBPACK_IMPORTED_MODULE_3__["default"]
});

/***/ }),

/***/ "./src/blocks/converter/save.js":
/*!**************************************!*\
  !*** ./src/blocks/converter/save.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ save)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {Element} Element to render.
 */

function save() {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    ..._wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps.save()
  });
}

/***/ }),

/***/ "./src/blocks/converter/style.scss":
/*!*****************************************!*\
  !*** ./src/blocks/converter/style.scss ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

"use strict";
module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"blocks/converter/index": 0,
/******/ 			"blocks/converter/style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkmulti_crypto_convert_wp_plugin"] = globalThis["webpackChunkmulti_crypto_convert_wp_plugin"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["blocks/converter/style-index"], () => (__webpack_require__("./src/blocks/converter/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map