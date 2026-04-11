import{r as p,K as B,j as s,L as F,$}from"./app-RgzCnkQW.js";import{A as P}from"./app-layout-BHNGl73p.js";/* empty css            */import"./app-logo-icon-CxWeqE5p.js";import"./index-5pJgHZ5R.js";import"./index-BILQy4VQ.js";let T={data:""},G=e=>typeof window=="object"?((e?e.querySelector("#_goober"):window._goober)||Object.assign((e||document.head).appendChild(document.createElement("style")),{innerHTML:" ",id:"_goober"})).firstChild:e||T,W=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,q=/\/\*[^]*?\*\/|  +/g,A=/\n+/g,g=(e,t)=>{let r="",o="",n="";for(let i in e){let a=e[i];i[0]=="@"?i[1]=="i"?r=i+" "+a+";":o+=i[1]=="f"?g(a,i):i+"{"+g(a,i[1]=="k"?"":t)+"}":typeof a=="object"?o+=g(a,t?t.replace(/([^,])+/g,l=>i.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,d=>/&/.test(d)?d.replace(/&/g,l):l?l+" "+d:d)):i):a!=null&&(i=/^--/.test(i)?i:i.replace(/[A-Z]/g,"-$&").toLowerCase(),n+=g.p?g.p(i,a):i+":"+a+";")}return r+(t&&n?t+"{"+n+"}":n)+o},x={},D=e=>{if(typeof e=="object"){let t="";for(let r in e)t+=r+D(e[r]);return t}return e},H=(e,t,r,o,n)=>{let i=D(e),a=x[i]||(x[i]=(d=>{let m=0,u=11;for(;m<d.length;)u=101*u+d.charCodeAt(m++)>>>0;return"go"+u})(i));if(!x[a]){let d=i!==e?e:(m=>{let u,b,y=[{}];for(;u=W.exec(m.replace(q,""));)u[4]?y.shift():u[3]?(b=u[3].replace(A," ").trim(),y.unshift(y[0][b]=y[0][b]||{})):y[0][u[1]]=u[2].replace(A," ").trim();return y[0]})(e);x[a]=g(n?{["@keyframes "+a]:d}:d,r?"":"."+a)}let l=r&&x.g?x.g:null;return r&&(x.g=x[a]),((d,m,u,b)=>{b?m.data=m.data.replace(b,d):m.data.indexOf(d)===-1&&(m.data=u?d+m.data:m.data+d)})(x[a],t,o,l),a},Z=(e,t,r)=>e.reduce((o,n,i)=>{let a=t[i];if(a&&a.call){let l=a(r),d=l&&l.props&&l.props.className||/^go/.test(l)&&l;a=d?"."+d:l&&typeof l=="object"?l.props?"":g(l,""):l===!1?"":l}return o+n+(a??"")},"");function N(e){let t=this||{},r=e.call?e(t.p):e;return H(r.unshift?r.raw?Z(r,[].slice.call(arguments,1),t.p):r.reduce((o,n)=>Object.assign(o,n&&n.call?n(t.p):n),{}):r,G(t.target),t.g,t.o,t.k)}let z,k,E;N.bind({g:1});let f=N.bind({k:1});function K(e,t,r,o){g.p=t,z=e,k=r,E=o}function h(e,t){let r=this||{};return function(){let o=arguments;function n(i,a){let l=Object.assign({},i),d=l.className||n.className;r.p=Object.assign({theme:k&&k()},l),r.o=/ *go\d+/.test(d),l.className=N.apply(r,o)+(d?" "+d:"");let m=e;return e[0]&&(m=l.as||e,delete l.as),E&&m[0]&&E(l),z(m,l)}return n}}var Q=e=>typeof e=="function",_=(e,t)=>Q(e)?e(t):e,Y=(()=>{let e=0;return()=>(++e).toString()})(),J=(()=>{let e;return()=>{if(e===void 0&&typeof window<"u"){let t=matchMedia("(prefers-reduced-motion: reduce)");e=!t||t.matches}return e}})(),U=20,I="default",C=(e,t)=>{let{toastLimit:r}=e.settings;switch(t.type){case 0:return{...e,toasts:[t.toast,...e.toasts].slice(0,r)};case 1:return{...e,toasts:e.toasts.map(a=>a.id===t.toast.id?{...a,...t.toast}:a)};case 2:let{toast:o}=t;return C(e,{type:e.toasts.find(a=>a.id===o.id)?1:0,toast:o});case 3:let{toastId:n}=t;return{...e,toasts:e.toasts.map(a=>a.id===n||n===void 0?{...a,dismissed:!0,visible:!1}:a)};case 4:return t.toastId===void 0?{...e,toasts:[]}:{...e,toasts:e.toasts.filter(a=>a.id!==t.toastId)};case 5:return{...e,pausedAt:t.time};case 6:let i=t.time-(e.pausedAt||0);return{...e,pausedAt:void 0,toasts:e.toasts.map(a=>({...a,pauseDuration:a.pauseDuration+i}))}}},V=[],X={toasts:[],pausedAt:void 0,settings:{toastLimit:U}},j={},M=(e,t=I)=>{j[t]=C(j[t]||X,e),V.forEach(([r,o])=>{r===t&&o(j[t])})},O=e=>Object.keys(j).forEach(t=>M(e,t)),ee=e=>Object.keys(j).find(t=>j[t].toasts.some(r=>r.id===e)),L=(e=I)=>t=>{M(t,e)},te=(e,t="blank",r)=>({createdAt:Date.now(),visible:!0,dismissed:!1,type:t,ariaProps:{role:"status","aria-live":"polite"},message:e,pauseDuration:0,...r,id:(r==null?void 0:r.id)||Y()}),w=e=>(t,r)=>{let o=te(t,e,r);return L(o.toasterId||ee(o.id))({type:2,toast:o}),o.id},c=(e,t)=>w("blank")(e,t);c.error=w("error");c.success=w("success");c.loading=w("loading");c.custom=w("custom");c.dismiss=(e,t)=>{let r={type:3,toastId:e};t?L(t)(r):O(r)};c.dismissAll=e=>c.dismiss(void 0,e);c.remove=(e,t)=>{let r={type:4,toastId:e};t?L(t)(r):O(r)};c.removeAll=e=>c.remove(void 0,e);c.promise=(e,t,r)=>{let o=c.loading(t.loading,{...r,...r==null?void 0:r.loading});return typeof e=="function"&&(e=e()),e.then(n=>{let i=t.success?_(t.success,n):void 0;return i?c.success(i,{id:o,...r,...r==null?void 0:r.success}):c.dismiss(o),n}).catch(n=>{let i=t.error?_(t.error,n):void 0;i?c.error(i,{id:o,...r,...r==null?void 0:r.error}):c.dismiss(o)}),e};var re=f`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
 transform: scale(1) rotate(45deg);
  opacity: 1;
}`,se=f`
from {
  transform: scale(0);
  opacity: 0;
}
to {
  transform: scale(1);
  opacity: 1;
}`,ae=f`
from {
  transform: scale(0) rotate(90deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(90deg);
	opacity: 1;
}`,oe=h("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#ff4b4b"};
  position: relative;
  transform: rotate(45deg);

  animation: ${re} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;

  &:after,
  &:before {
    content: '';
    animation: ${se} 0.15s ease-out forwards;
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
    animation: ${ae} 0.15s ease-out forwards;
    animation-delay: 180ms;
    transform: rotate(90deg);
  }
`,ie=f`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`,ne=h("div")`
  width: 12px;
  height: 12px;
  box-sizing: border-box;
  border: 2px solid;
  border-radius: 100%;
  border-color: ${e=>e.secondary||"#e0e0e0"};
  border-right-color: ${e=>e.primary||"#616161"};
  animation: ${ie} 1s linear infinite;
`,le=f`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(45deg);
	opacity: 1;
}`,de=f`
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
}`,ce=h("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#61d345"};
  position: relative;
  transform: rotate(45deg);

  animation: ${le} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;
  &:after {
    content: '';
    box-sizing: border-box;
    animation: ${de} 0.2s ease-out forwards;
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
`,me=h("div")`
  position: absolute;
`,pe=h("div")`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 20px;
  min-height: 20px;
`,ue=f`
from {
  transform: scale(0.6);
  opacity: 0.4;
}
to {
  transform: scale(1);
  opacity: 1;
}`,xe=h("div")`
  position: relative;
  transform: scale(0.6);
  opacity: 0.4;
  min-width: 20px;
  animation: ${ue} 0.3s 0.12s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
`,fe=({toast:e})=>{let{icon:t,type:r,iconTheme:o}=e;return t!==void 0?typeof t=="string"?p.createElement(xe,null,t):t:r==="blank"?null:p.createElement(pe,null,p.createElement(ne,{...o}),r!=="loading"&&p.createElement(me,null,r==="error"?p.createElement(oe,{...o}):p.createElement(ce,{...o})))},ge=e=>`
0% {transform: translate3d(0,${e*-200}%,0) scale(.6); opacity:.5;}
100% {transform: translate3d(0,0,0) scale(1); opacity:1;}
`,he=e=>`
0% {transform: translate3d(0,0,-1px) scale(1); opacity:1;}
100% {transform: translate3d(0,${e*-150}%,-1px) scale(.6); opacity:0;}
`,be="0%{opacity:0;} 100%{opacity:1;}",ye="0%{opacity:1;} 100%{opacity:0;}",ve=h("div")`
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
`,je=h("div")`
  display: flex;
  justify-content: center;
  margin: 4px 10px;
  color: inherit;
  flex: 1 1 auto;
  white-space: pre-line;
`,we=(e,t)=>{let r=e.includes("top")?1:-1,[o,n]=J()?[be,ye]:[ge(r),he(r)];return{animation:t?`${f(o)} 0.35s cubic-bezier(.21,1.02,.73,1) forwards`:`${f(n)} 0.4s forwards cubic-bezier(.06,.71,.55,1)`}};p.memo(({toast:e,position:t,style:r,children:o})=>{let n=e.height?we(e.position||t||"top-center",e.visible):{opacity:0},i=p.createElement(fe,{toast:e}),a=p.createElement(je,{...e.ariaProps},_(e.message,e));return p.createElement(ve,{className:e.className,style:{...n,...r,...e.style}},typeof o=="function"?o({icon:i,message:a}):p.createElement(p.Fragment,null,i,a))});K(p.createElement);N`
  z-index: 9999;
  > * {
    pointer-events: auto;
  }
`;var Ne=c;const $e={1:"Pending",2:"Approved",3:"Rejected"},S={1:{badge:"bg-yellow-100 text-yellow-800",dot:"bg-yellow-500"},2:{badge:"bg-green-100 text-green-800",dot:"bg-green-600"},3:{badge:"bg-red-100 text-red-800",dot:"bg-red-600"}};function R({status:e}){const t=S[e]??S[1];return s.jsxs("span",{className:`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ${t.badge}`,children:[s.jsx("span",{className:`w-1.5 h-1.5 rounded-full ${t.dot}`}),$e[e]??"Pending"]})}function ke({name:e,image:t}){const r=(e==null?void 0:e.split(" ").map(o=>o[0]).join("").toUpperCase().slice(0,2))??"?";return t?s.jsx("img",{src:t,alt:e,className:"w-20 h-20 rounded-full object-cover border-2 border-white shadow"}):s.jsx("div",{className:"w-20 h-20 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-2xl font-bold border-2 border-white shadow",children:r})}function v({label:e,value:t}){return s.jsxs("div",{className:"flex flex-col gap-0.5 py-2 border-b border-gray-100 last:border-0",children:[s.jsx("span",{className:"text-xs text-gray-500",children:e}),s.jsx("span",{className:"text-sm font-medium text-gray-900",children:t||"—"})]})}function De(){const{rider:e,toastMessage:t}=B().props;return p.useEffect(()=>{t&&Ne.success(t)},[t]),s.jsxs(P,{children:[s.jsx(F,{title:`Rider Review - ${e.name}`}),s.jsxs("div",{className:"p-4 md:p-6 max-w-5xl mx-auto space-y-6",children:[s.jsxs("div",{className:"flex flex-col md:flex-row md:items-center md:justify-between gap-3",children:[s.jsxs("div",{children:[s.jsx($,{href:route("riders.account.review.request"),className:"text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-1",children:"← Back to Riders"}),s.jsx("h1",{className:"text-xl font-bold text-gray-900",children:"Rider Review"})]}),s.jsx(R,{status:e.status??1})]}),s.jsxs("div",{className:"bg-white border border-gray-200 rounded-xl p-5",children:[s.jsx("h2",{className:"text-sm font-semibold text-gray-700 mb-4",children:"Rider Information"}),s.jsxs("div",{className:"flex flex-col md:flex-row gap-5",children:[s.jsx("div",{className:"flex-shrink-0",children:s.jsx(ke,{name:e.name,image:e.profile_image})}),s.jsxs("div",{className:"flex-1 grid grid-cols-1 md:grid-cols-2 gap-x-8",children:[s.jsx(v,{label:"Full Name",value:e.name}),s.jsx(v,{label:"Email",value:e.email}),s.jsx(v,{label:"Phone",value:e.phone_number}),s.jsx(v,{label:"Gender",value:e.gender}),s.jsx(v,{label:"Date of Birth",value:e.dob}),s.jsx(v,{label:"Member Since",value:new Date(e.created_at).toLocaleDateString("en-GB",{day:"2-digit",month:"short",year:"numeric"})})]})]})]}),s.jsxs("div",{children:[s.jsx("h2",{className:"text-sm font-semibold text-gray-700 mb-3",children:"Submitted Documents"}),e.user_riders.length>0?s.jsx("div",{className:"space-y-4",children:e.user_riders.map(r=>{const o=r.review_status??1;return s.jsxs("div",{className:"bg-white border border-gray-200 rounded-xl p-5",children:[s.jsxs("div",{className:"flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4",children:[s.jsxs("div",{children:[s.jsx("p",{className:"text-sm font-semibold text-gray-900",children:r.document_type}),s.jsxs("p",{className:"text-xs text-gray-500 mt-0.5",children:["#",r.document_number]})]}),s.jsx(R,{status:o})]}),r.remarks&&s.jsx("div",{className:"bg-yellow-50 border border-yellow-100 rounded-lg px-3 py-2 mb-4",children:s.jsxs("p",{className:"text-xs text-yellow-800",children:[s.jsx("span",{className:"font-semibold",children:"Remarks: "}),r.remarks]})}),r.document.length>0&&s.jsx("div",{className:"grid grid-cols-1 md:grid-cols-3 gap-3",children:r.document.map((n,i)=>s.jsx("a",{href:n,target:"_blank",rel:"noopener noreferrer",className:"block group",children:s.jsx("img",{src:n,alt:`${r.document_type} ${i+1}`,className:"w-full h-48 object-cover rounded-lg border border-gray-200 group-hover:opacity-90 transition"})},i))}),s.jsxs("p",{className:"text-xs text-gray-400 mt-3",children:["Submitted: ",new Date(r.created_at).toLocaleDateString("en-GB",{day:"2-digit",month:"short",year:"numeric"})]})]},r.id)})}):s.jsx("div",{className:"bg-white border border-gray-200 rounded-xl p-8 text-center",children:s.jsx("p",{className:"text-sm text-gray-400",children:"No documents submitted yet"})})]}),s.jsxs("div",{className:"bg-white border border-gray-200 rounded-xl p-5",children:[s.jsx("p",{className:"text-xs text-gray-500 mb-3",children:"Take action on this rider's account"}),s.jsxs("div",{className:"flex gap-3 flex-wrap",children:[s.jsxs($,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:2}),className:"inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors",children:[s.jsx("svg",{className:"w-3.5 h-3.5",viewBox:"0 0 16 16",fill:"none",children:s.jsx("path",{d:"M3 8.5L6.5 12L13 5",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round"})}),"Approve Rider"]}),s.jsxs($,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:3}),className:"inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg transition-colors",children:[s.jsx("svg",{className:"w-3.5 h-3.5",viewBox:"0 0 16 16",fill:"none",children:s.jsx("path",{d:"M4 4L12 12M12 4L4 12",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round"})}),"Reject Rider"]})]})]})]})]})}export{De as default};
