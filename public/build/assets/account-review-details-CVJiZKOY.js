import{r as p,K as C,j as s,L as M,$ as N}from"./app-B_eTE87r.js";import{A as P}from"./app-layout-1Zh2g1Le.js";/* empty css            */import"./app-logo-icon-Bu5hudfU.js";import"./index-B-zvCzRK.js";import"./index-BvRVraP5.js";let F={data:""},B=e=>typeof window=="object"?((e?e.querySelector("#_goober"):window._goober)||Object.assign((e||document.head).appendChild(document.createElement("style")),{innerHTML:" ",id:"_goober"})).firstChild:e||F,T=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,W=/\/\*[^]*?\*\/|  +/g,A=/\n+/g,x=(e,t)=>{let r="",i="",n="";for(let o in e){let a=e[o];o[0]=="@"?o[1]=="i"?r=o+" "+a+";":i+=o[1]=="f"?x(a,o):o+"{"+x(a,o[1]=="k"?"":t)+"}":typeof a=="object"?i+=x(a,t?t.replace(/([^,])+/g,d=>o.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,l=>/&/.test(l)?l.replace(/&/g,d):d?d+" "+l:l)):o):a!=null&&(o=/^--/.test(o)?o:o.replace(/[A-Z]/g,"-$&").toLowerCase(),n+=x.p?x.p(o,a):o+":"+a+";")}return r+(t&&n?t+"{"+n+"}":n)+i},g={},S=e=>{if(typeof e=="object"){let t="";for(let r in e)t+=r+S(e[r]);return t}return e},q=(e,t,r,i,n)=>{let o=S(e),a=g[o]||(g[o]=(l=>{let m=0,u=11;for(;m<l.length;)u=101*u+l.charCodeAt(m++)>>>0;return"go"+u})(o));if(!g[a]){let l=o!==e?e:(m=>{let u,b,y=[{}];for(;u=T.exec(m.replace(W,""));)u[4]?y.shift():u[3]?(b=u[3].replace(A," ").trim(),y.unshift(y[0][b]=y[0][b]||{})):y[0][u[1]]=u[2].replace(A," ").trim();return y[0]})(e);g[a]=x(n?{["@keyframes "+a]:l}:l,r?"":"."+a)}let d=r&&g.g?g.g:null;return r&&(g.g=g[a]),((l,m,u,b)=>{b?m.data=m.data.replace(b,l):m.data.indexOf(l)===-1&&(m.data=u?l+m.data:m.data+l)})(g[a],t,i,d),a},H=(e,t,r)=>e.reduce((i,n,o)=>{let a=t[o];if(a&&a.call){let d=a(r),l=d&&d.props&&d.props.className||/^go/.test(d)&&d;a=l?"."+l:d&&typeof d=="object"?d.props?"":x(d,""):d===!1?"":d}return i+n+(a??"")},"");function w(e){let t=this||{},r=e.call?e(t.p):e;return q(r.unshift?r.raw?H(r,[].slice.call(arguments,1),t.p):r.reduce((i,n)=>Object.assign(i,n&&n.call?n(t.p):n),{}):r,B(t.target),t.g,t.o,t.k)}let R,$,k;w.bind({g:1});let f=w.bind({k:1});function Z(e,t,r,i){x.p=t,R=e,$=r,k=i}function h(e,t){let r=this||{};return function(){let i=arguments;function n(o,a){let d=Object.assign({},o),l=d.className||n.className;r.p=Object.assign({theme:$&&$()},d),r.o=/ *go\d+/.test(l),d.className=w.apply(r,i)+(l?" "+l:"");let m=e;return e[0]&&(m=d.as||e,delete d.as),k&&m[0]&&k(d),R(m,d)}return n}}var G=e=>typeof e=="function",E=(e,t)=>G(e)?e(t):e,K=(()=>{let e=0;return()=>(++e).toString()})(),Q=(()=>{let e;return()=>{if(e===void 0&&typeof window<"u"){let t=matchMedia("(prefers-reduced-motion: reduce)");e=!t||t.matches}return e}})(),Y=20,z="default",D=(e,t)=>{let{toastLimit:r}=e.settings;switch(t.type){case 0:return{...e,toasts:[t.toast,...e.toasts].slice(0,r)};case 1:return{...e,toasts:e.toasts.map(a=>a.id===t.toast.id?{...a,...t.toast}:a)};case 2:let{toast:i}=t;return D(e,{type:e.toasts.find(a=>a.id===i.id)?1:0,toast:i});case 3:let{toastId:n}=t;return{...e,toasts:e.toasts.map(a=>a.id===n||n===void 0?{...a,dismissed:!0,visible:!1}:a)};case 4:return t.toastId===void 0?{...e,toasts:[]}:{...e,toasts:e.toasts.filter(a=>a.id!==t.toastId)};case 5:return{...e,pausedAt:t.time};case 6:let o=t.time-(e.pausedAt||0);return{...e,pausedAt:void 0,toasts:e.toasts.map(a=>({...a,pauseDuration:a.pauseDuration+o}))}}},J=[],U={toasts:[],pausedAt:void 0,settings:{toastLimit:Y}},v={},I=(e,t=z)=>{v[t]=D(v[t]||U,e),J.forEach(([r,i])=>{r===t&&i(v[t])})},O=e=>Object.keys(v).forEach(t=>I(e,t)),V=e=>Object.keys(v).find(t=>v[t].toasts.some(r=>r.id===e)),_=(e=z)=>t=>{I(t,e)},X=(e,t="blank",r)=>({createdAt:Date.now(),visible:!0,dismissed:!1,type:t,ariaProps:{role:"status","aria-live":"polite"},message:e,pauseDuration:0,...r,id:(r==null?void 0:r.id)||K()}),j=e=>(t,r)=>{let i=X(t,e,r);return _(i.toasterId||V(i.id))({type:2,toast:i}),i.id},c=(e,t)=>j("blank")(e,t);c.error=j("error");c.success=j("success");c.loading=j("loading");c.custom=j("custom");c.dismiss=(e,t)=>{let r={type:3,toastId:e};t?_(t)(r):O(r)};c.dismissAll=e=>c.dismiss(void 0,e);c.remove=(e,t)=>{let r={type:4,toastId:e};t?_(t)(r):O(r)};c.removeAll=e=>c.remove(void 0,e);c.promise=(e,t,r)=>{let i=c.loading(t.loading,{...r,...r==null?void 0:r.loading});return typeof e=="function"&&(e=e()),e.then(n=>{let o=t.success?E(t.success,n):void 0;return o?c.success(o,{id:i,...r,...r==null?void 0:r.success}):c.dismiss(i),n}).catch(n=>{let o=t.error?E(t.error,n):void 0;o?c.error(o,{id:i,...r,...r==null?void 0:r.error}):c.dismiss(i)}),e};var ee=f`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
 transform: scale(1) rotate(45deg);
  opacity: 1;
}`,te=f`
from {
  transform: scale(0);
  opacity: 0;
}
to {
  transform: scale(1);
  opacity: 1;
}`,re=f`
from {
  transform: scale(0) rotate(90deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(90deg);
	opacity: 1;
}`,se=h("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#ff4b4b"};
  position: relative;
  transform: rotate(45deg);

  animation: ${ee} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;

  &:after,
  &:before {
    content: '';
    animation: ${te} 0.15s ease-out forwards;
    animation-delay: 150ms;
    position: absolute;
    border-radius: 3px;
    opacity: 0;
    background: ${e=>e.secondary||"#fff"};
    bottom: 9px;
    left: 4px;
    height: 2px;
    width: 12px;
  }

  &:before {
    animation: ${re} 0.15s ease-out forwards;
    animation-delay: 180ms;
    transform: rotate(90deg);
  }
`,ae=f`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`,ie=h("div")`
  width: 12px;
  height: 12px;
  box-sizing: border-box;
  border: 2px solid;
  border-radius: 100%;
  border-color: ${e=>e.secondary||"#e0e0e0"};
  border-right-color: ${e=>e.primary||"#616161"};
  animation: ${ae} 1s linear infinite;
`,oe=f`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(45deg);
	opacity: 1;
}`,ne=f`
0% {
	height: 0;
	width: 0;
	opacity: 0;
}
40% {
  height: 0;
	width: 6px;
	opacity: 1;
}
100% {
  opacity: 1;
  height: 10px;
}`,de=h("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#61d345"};
  position: relative;
  transform: rotate(45deg);

  animation: ${oe} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;
  &:after {
    content: '';
    box-sizing: border-box;
    animation: ${ne} 0.2s ease-out forwards;
    opacity: 0;
    animation-delay: 200ms;
    position: absolute;
    border-right: 2px solid;
    border-bottom: 2px solid;
    border-color: ${e=>e.secondary||"#fff"};
    bottom: 6px;
    left: 6px;
    height: 10px;
    width: 6px;
  }
`,le=h("div")`
  position: absolute;
`,ce=h("div")`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 20px;
  min-height: 20px;
`,me=f`
from {
  transform: scale(0.6);
  opacity: 0.4;
}
to {
  transform: scale(1);
  opacity: 1;
}`,pe=h("div")`
  position: relative;
  transform: scale(0.6);
  opacity: 0.4;
  min-width: 20px;
  animation: ${me} 0.3s 0.12s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
`,ue=({toast:e})=>{let{icon:t,type:r,iconTheme:i}=e;return t!==void 0?typeof t=="string"?p.createElement(pe,null,t):t:r==="blank"?null:p.createElement(ce,null,p.createElement(ie,{...i}),r!=="loading"&&p.createElement(le,null,r==="error"?p.createElement(se,{...i}):p.createElement(de,{...i})))},ge=e=>`
0% {transform: translate3d(0,${e*-200}%,0) scale(.6); opacity:.5;}
100% {transform: translate3d(0,0,0) scale(1); opacity:1;}
`,fe=e=>`
0% {transform: translate3d(0,0,-1px) scale(1); opacity:1;}
100% {transform: translate3d(0,${e*-150}%,-1px) scale(.6); opacity:0;}
`,xe="0%{opacity:0;} 100%{opacity:1;}",he="0%{opacity:1;} 100%{opacity:0;}",be=h("div")`
  display: flex;
  align-items: center;
  background: #fff;
  color: #363636;
  line-height: 1.3;
  will-change: transform;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1), 0 3px 3px rgba(0, 0, 0, 0.05);
  max-width: 350px;
  pointer-events: auto;
  padding: 8px 10px;
  border-radius: 8px;
`,ye=h("div")`
  display: flex;
  justify-content: center;
  margin: 4px 10px;
  color: inherit;
  flex: 1 1 auto;
  white-space: pre-line;
`,ve=(e,t)=>{let r=e.includes("top")?1:-1,[i,n]=Q()?[xe,he]:[ge(r),fe(r)];return{animation:t?`${f(i)} 0.35s cubic-bezier(.21,1.02,.73,1) forwards`:`${f(n)} 0.4s forwards cubic-bezier(.06,.71,.55,1)`}};p.memo(({toast:e,position:t,style:r,children:i})=>{let n=e.height?ve(e.position||t||"top-center",e.visible):{opacity:0},o=p.createElement(ue,{toast:e}),a=p.createElement(ye,{...e.ariaProps},E(e.message,e));return p.createElement(be,{className:e.className,style:{...n,...r,...e.style}},typeof i=="function"?i({icon:o,message:a}):p.createElement(p.Fragment,null,o,a))});Z(p.createElement);w`
  z-index: 9999;
  > * {
    pointer-events: auto;
  }
`;var je=c;const we={1:"Pending",2:"Approved",3:"Rejected"},L={1:{badge:"bg-amber-50 text-amber-800 border-amber-300",dot:"bg-amber-500"},2:{badge:"bg-green-50 text-green-800 border-green-300",dot:"bg-green-600"},3:{badge:"bg-red-50 text-red-800 border-red-300",dot:"bg-red-600"}};function Ne({status:e}){const t=L[e]??L[1];return s.jsxs("span",{className:`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border ${t.badge}`,children:[s.jsx("span",{className:`w-1.5 h-1.5 rounded-full ${t.dot}`}),we[e]??"Pending"]})}function Se(){const{rider:e,toastMessage:t}=C().props;return p.useEffect(()=>{t&&je.success(t)},[t]),s.jsxs(P,{children:[s.jsx(M,{title:`Rider Review - ${e.name}`}),s.jsxs("div",{className:"w-full p-4 bg-white shadow rounded",children:[s.jsx("div",{className:"mb-6",children:s.jsx(N,{href:route("riders.account.review.request"),className:"text-blue-500 hover:underline",children:"← Back to Riders"})}),s.jsxs("div",{className:"border p-4 rounded-lg shadow-sm mb-6",children:[s.jsx("h1",{className:"text-2xl font-bold mb-4",children:"Rider Information"}),s.jsxs("div",{className:"grid grid-cols-1 md:grid-cols-2 gap-6",children:[s.jsxs("div",{className:"space-y-1",children:[s.jsxs("p",{children:[s.jsx("strong",{children:"Name:"})," ",e.name]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Email:"})," ",e.email]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Phone:"})," ",e.phone_number]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Gender:"})," ",e.gender||"N/A"]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Date of Birth:"})," ",e.dob||"N/A"]})]}),s.jsx("div",{className:"flex items-center",children:e.profile_image?s.jsx("img",{src:e.profile_image,alt:e.name,className:"w-32 h-32 rounded-full object-cover border"}):s.jsx("p",{children:"No profile image"})})]})]}),s.jsxs("div",{children:[s.jsx("h2",{className:"text-xl font-semibold mb-4",children:"Submitted Documents"}),e.user_riders.length>0?e.user_riders.map(r=>{const i=r.review_status??1;return s.jsxs("div",{className:"border p-4 rounded-lg shadow-sm mb-6",children:[s.jsxs("div",{className:"space-y-1 mb-3",children:[s.jsxs("p",{children:[s.jsx("strong",{children:"Document Type:"})," ",r.document_type]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Document Number:"})," ",r.document_number]}),s.jsxs("div",{className:"flex items-center gap-2",children:[s.jsx("strong",{className:"text-sm",children:"Review Status:"}),s.jsx(Ne,{status:i})]}),r.remarks&&s.jsxs("p",{children:[s.jsx("strong",{children:"Remarks:"})," ",r.remarks]})]}),s.jsx("div",{className:"grid grid-cols-1 md:grid-cols-3 gap-4 mt-4",children:r.document.map((n,o)=>s.jsx("img",{src:n,alt:`${r.document_type} ${o+1}`,className:"w-full h-48 object-cover rounded border"},o))})]},r.id)}):s.jsx("p",{children:"No documents submitted"})]}),s.jsxs("div",{className:"mt-6 border rounded-xl overflow-hidden",children:[s.jsx("hr",{className:"border-t border-gray-100"}),s.jsxs("div",{className:"px-5 py-4 bg-white",children:[s.jsx("p",{className:"text-xs text-gray-500 mb-3",children:"Take action on this rider's account"}),s.jsxs("div",{className:"flex gap-2.5 flex-wrap",children:[s.jsxs(N,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:2}),className:"inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 hover:bg-green-800 text-green-50 text-sm font-medium rounded-lg transition-colors",children:[s.jsx("svg",{className:"w-3.5 h-3.5",viewBox:"0 0 16 16",fill:"none",children:s.jsx("path",{d:"M3 8.5L6.5 12L13 5",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round"})}),"Approve Rider"]}),s.jsxs(N,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:3}),className:"inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-red-50 text-red-700 border border-red-300 text-sm font-medium rounded-lg transition-colors",children:[s.jsx("svg",{className:"w-3.5 h-3.5",viewBox:"0 0 16 16",fill:"none",children:s.jsx("path",{d:"M4 4L12 12M12 4L4 12",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round"})}),"Reject"]})]})]})]})]})]})}export{Se as default};
