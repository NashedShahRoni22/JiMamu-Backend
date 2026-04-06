import{r as m,K as I,j as s,L as O,$ as N}from"./app-DyljCYCc.js";import{A as M}from"./app-layout-BOBs97U8.js";/* empty css            */import"./app-logo-icon-pGjwP9N1.js";import"./index-WFQHHGZu.js";import"./index-AKaEjK7Q.js";let F={data:""},P=e=>typeof window=="object"?((e?e.querySelector("#_goober"):window._goober)||Object.assign((e||document.head).appendChild(document.createElement("style")),{innerHTML:" ",id:"_goober"})).firstChild:e||F,T=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,B=/\/\*[^]*?\*\/|  +/g,L=/\n+/g,g=(e,t)=>{let r="",a="",n="";for(let o in e){let i=e[o];o[0]=="@"?o[1]=="i"?r=o+" "+i+";":a+=o[1]=="f"?g(i,o):o+"{"+g(i,o[1]=="k"?"":t)+"}":typeof i=="object"?a+=g(i,t?t.replace(/([^,])+/g,d=>o.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,l=>/&/.test(l)?l.replace(/&/g,d):d?d+" "+l:l)):o):i!=null&&(o=/^--/.test(o)?o:o.replace(/[A-Z]/g,"-$&").toLowerCase(),n+=g.p?g.p(o,i):o+":"+i+";")}return r+(t&&n?t+"{"+n+"}":n)+a},x={},A=e=>{if(typeof e=="object"){let t="";for(let r in e)t+=r+A(e[r]);return t}return e},W=(e,t,r,a,n)=>{let o=A(e),i=x[o]||(x[o]=(l=>{let p=0,u=11;for(;p<l.length;)u=101*u+l.charCodeAt(p++)>>>0;return"go"+u})(o));if(!x[i]){let l=o!==e?e:(p=>{let u,b,y=[{}];for(;u=T.exec(p.replace(B,""));)u[4]?y.shift():u[3]?(b=u[3].replace(L," ").trim(),y.unshift(y[0][b]=y[0][b]||{})):y[0][u[1]]=u[2].replace(L," ").trim();return y[0]})(e);x[i]=g(n?{["@keyframes "+i]:l}:l,r?"":"."+i)}let d=r&&x.g?x.g:null;return r&&(x.g=x[i]),((l,p,u,b)=>{b?p.data=p.data.replace(b,l):p.data.indexOf(l)===-1&&(p.data=u?l+p.data:p.data+l)})(x[i],t,a,d),i},q=(e,t,r)=>e.reduce((a,n,o)=>{let i=t[o];if(i&&i.call){let d=i(r),l=d&&d.props&&d.props.className||/^go/.test(d)&&d;i=l?"."+l:d&&typeof d=="object"?d.props?"":g(d,""):d===!1?"":d}return a+n+(i??"")},"");function w(e){let t=this||{},r=e.call?e(t.p):e;return W(r.unshift?r.raw?q(r,[].slice.call(arguments,1),t.p):r.reduce((a,n)=>Object.assign(a,n&&n.call?n(t.p):n),{}):r,P(t.target),t.g,t.o,t.k)}let R,$,k;w.bind({g:1});let f=w.bind({k:1});function H(e,t,r,a){g.p=t,R=e,$=r,k=a}function h(e,t){let r=this||{};return function(){let a=arguments;function n(o,i){let d=Object.assign({},o),l=d.className||n.className;r.p=Object.assign({theme:$&&$()},d),r.o=/ *go\d+/.test(l),d.className=w.apply(r,a)+(l?" "+l:"");let p=e;return e[0]&&(p=d.as||e,delete d.as),k&&p[0]&&k(d),R(p,d)}return n}}var Z=e=>typeof e=="function",E=(e,t)=>Z(e)?e(t):e,G=(()=>{let e=0;return()=>(++e).toString()})(),K=(()=>{let e;return()=>{if(e===void 0&&typeof window<"u"){let t=matchMedia("(prefers-reduced-motion: reduce)");e=!t||t.matches}return e}})(),Q=20,D="default",S=(e,t)=>{let{toastLimit:r}=e.settings;switch(t.type){case 0:return{...e,toasts:[t.toast,...e.toasts].slice(0,r)};case 1:return{...e,toasts:e.toasts.map(i=>i.id===t.toast.id?{...i,...t.toast}:i)};case 2:let{toast:a}=t;return S(e,{type:e.toasts.find(i=>i.id===a.id)?1:0,toast:a});case 3:let{toastId:n}=t;return{...e,toasts:e.toasts.map(i=>i.id===n||n===void 0?{...i,dismissed:!0,visible:!1}:i)};case 4:return t.toastId===void 0?{...e,toasts:[]}:{...e,toasts:e.toasts.filter(i=>i.id!==t.toastId)};case 5:return{...e,pausedAt:t.time};case 6:let o=t.time-(e.pausedAt||0);return{...e,pausedAt:void 0,toasts:e.toasts.map(i=>({...i,pauseDuration:i.pauseDuration+o}))}}},Y=[],J={toasts:[],pausedAt:void 0,settings:{toastLimit:Q}},v={},z=(e,t=D)=>{v[t]=S(v[t]||J,e),Y.forEach(([r,a])=>{r===t&&a(v[t])})},C=e=>Object.keys(v).forEach(t=>z(e,t)),U=e=>Object.keys(v).find(t=>v[t].toasts.some(r=>r.id===e)),_=(e=D)=>t=>{z(t,e)},V=(e,t="blank",r)=>({createdAt:Date.now(),visible:!0,dismissed:!1,type:t,ariaProps:{role:"status","aria-live":"polite"},message:e,pauseDuration:0,...r,id:(r==null?void 0:r.id)||G()}),j=e=>(t,r)=>{let a=V(t,e,r);return _(a.toasterId||U(a.id))({type:2,toast:a}),a.id},c=(e,t)=>j("blank")(e,t);c.error=j("error");c.success=j("success");c.loading=j("loading");c.custom=j("custom");c.dismiss=(e,t)=>{let r={type:3,toastId:e};t?_(t)(r):C(r)};c.dismissAll=e=>c.dismiss(void 0,e);c.remove=(e,t)=>{let r={type:4,toastId:e};t?_(t)(r):C(r)};c.removeAll=e=>c.remove(void 0,e);c.promise=(e,t,r)=>{let a=c.loading(t.loading,{...r,...r==null?void 0:r.loading});return typeof e=="function"&&(e=e()),e.then(n=>{let o=t.success?E(t.success,n):void 0;return o?c.success(o,{id:a,...r,...r==null?void 0:r.success}):c.dismiss(a),n}).catch(n=>{let o=t.error?E(t.error,n):void 0;o?c.error(o,{id:a,...r,...r==null?void 0:r.error}):c.dismiss(a)}),e};var X=f`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
 transform: scale(1) rotate(45deg);
  opacity: 1;
}`,ee=f`
from {
  transform: scale(0);
  opacity: 0;
}
to {
  transform: scale(1);
  opacity: 1;
}`,te=f`
from {
  transform: scale(0) rotate(90deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(90deg);
	opacity: 1;
}`,re=h("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#ff4b4b"};
  position: relative;
  transform: rotate(45deg);

  animation: ${X} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;

  &:after,
  &:before {
    content: '';
    animation: ${ee} 0.15s ease-out forwards;
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
    animation: ${te} 0.15s ease-out forwards;
    animation-delay: 180ms;
    transform: rotate(90deg);
  }
`,se=f`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`,ae=h("div")`
  width: 12px;
  height: 12px;
  box-sizing: border-box;
  border: 2px solid;
  border-radius: 100%;
  border-color: ${e=>e.secondary||"#e0e0e0"};
  border-right-color: ${e=>e.primary||"#616161"};
  animation: ${se} 1s linear infinite;
`,ie=f`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(45deg);
	opacity: 1;
}`,oe=f`
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
}`,ne=h("div")`
  width: 20px;
  opacity: 0;
  height: 20px;
  border-radius: 10px;
  background: ${e=>e.primary||"#61d345"};
  position: relative;
  transform: rotate(45deg);

  animation: ${ie} 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
  animation-delay: 100ms;
  &:after {
    content: '';
    box-sizing: border-box;
    animation: ${oe} 0.2s ease-out forwards;
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
`,de=h("div")`
  position: absolute;
`,le=h("div")`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 20px;
  min-height: 20px;
`,ce=f`
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
  animation: ${ce} 0.3s 0.12s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
`,me=({toast:e})=>{let{icon:t,type:r,iconTheme:a}=e;return t!==void 0?typeof t=="string"?m.createElement(pe,null,t):t:r==="blank"?null:m.createElement(le,null,m.createElement(ae,{...a}),r!=="loading"&&m.createElement(de,null,r==="error"?m.createElement(re,{...a}):m.createElement(ne,{...a})))},ue=e=>`
0% {transform: translate3d(0,${e*-200}%,0) scale(.6); opacity:.5;}
100% {transform: translate3d(0,0,0) scale(1); opacity:1;}
`,xe=e=>`
0% {transform: translate3d(0,0,-1px) scale(1); opacity:1;}
100% {transform: translate3d(0,${e*-150}%,-1px) scale(.6); opacity:0;}
`,fe="0%{opacity:0;} 100%{opacity:1;}",ge="0%{opacity:1;} 100%{opacity:0;}",he=h("div")`
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
`,be=h("div")`
  display: flex;
  justify-content: center;
  margin: 4px 10px;
  color: inherit;
  flex: 1 1 auto;
  white-space: pre-line;
`,ye=(e,t)=>{let r=e.includes("top")?1:-1,[a,n]=K()?[fe,ge]:[ue(r),xe(r)];return{animation:t?`${f(a)} 0.35s cubic-bezier(.21,1.02,.73,1) forwards`:`${f(n)} 0.4s forwards cubic-bezier(.06,.71,.55,1)`}};m.memo(({toast:e,position:t,style:r,children:a})=>{let n=e.height?ye(e.position||t||"top-center",e.visible):{opacity:0},o=m.createElement(me,{toast:e}),i=m.createElement(be,{...e.ariaProps},E(e.message,e));return m.createElement(he,{className:e.className,style:{...n,...r,...e.style}},typeof a=="function"?a({icon:o,message:i}):m.createElement(m.Fragment,null,o,i))});H(m.createElement);w`
  z-index: 9999;
  > * {
    pointer-events: auto;
  }
`;var ve=c;function _e(){const{rider:e,toastMessage:t}=I().props,r={1:"Pending",2:"Approved",3:"Rejected"};return m.useEffect(()=>{t&&ve.success(t)},[t]),s.jsxs(M,{children:[s.jsx(O,{title:`Rider Review - ${e.name}`}),s.jsxs("div",{className:"w-full p-4 bg-white shadow rounded",children:[s.jsx("div",{className:"mb-6",children:s.jsx(N,{href:route("riders.account.review.request"),className:"text-blue-500 hover:underline",children:"← Back to Riders"})}),s.jsxs("div",{className:"border p-4 rounded-lg shadow-sm mb-6",children:[s.jsx("h1",{className:"text-2xl font-bold mb-4",children:"Rider Information"}),s.jsxs("div",{className:"grid grid-cols-1 md:grid-cols-2 gap-6",children:[s.jsxs("div",{children:[s.jsxs("p",{children:[s.jsx("strong",{children:"Name:"})," ",e.name]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Email:"})," ",e.email]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Phone:"})," ",e.phone_number]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Gender:"})," ",e.gender||"N/A"]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Date of Birth:"})," ",e.dob||"N/A"]})]}),s.jsx("div",{className:"flex items-center",children:e.profile_image?s.jsx("img",{src:e.profile_image,alt:e.name,className:"w-32 h-32 rounded-full object-cover border"}):s.jsx("p",{children:"No profile image"})})]})]}),s.jsxs("div",{children:[s.jsx("h2",{className:"text-xl font-semibold mb-4",children:"Submitted Documents"}),e.user_riders.length>0?e.user_riders.map(a=>s.jsxs("div",{className:"border p-4 rounded-lg shadow-sm mb-6",children:[s.jsxs("p",{children:[s.jsx("strong",{children:"Document Type:"})," ",a.document_type]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Document Number:"})," ",a.document_number]}),s.jsxs("p",{children:[s.jsx("strong",{children:"Review Status:"})," ",r[a.review_status]]}),a.remarks&&s.jsxs("p",{children:[s.jsx("strong",{children:"Remarks:"})," ",a.remarks]}),s.jsx("div",{className:"grid grid-cols-1 md:grid-cols-3 gap-4 mt-4",children:a.document.map((n,o)=>s.jsx("img",{src:n,alt:`${a.document_type} ${o+1}`,className:"w-full h-48 object-cover rounded border"},o))})]},a.id)):s.jsx("p",{children:"No documents submitted"})]}),s.jsxs("div",{className:"mt-6 border rounded-xl overflow-hidden",children:[s.jsxs("div",{className:"px-5 py-4 flex items-center justify-between flex-wrap gap-2 bg-white",children:[s.jsxs("div",{children:[s.jsx("p",{className:"text-xs text-gray-500 mb-1.5",children:"Current review status"}),s.jsxs("span",{className:`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border
        ${e.status===1?"bg-amber-50 text-amber-800 border-amber-300":e.status===2?"bg-green-50 text-green-800 border-green-300":"bg-red-50 text-red-800 border-red-300"}`,children:[s.jsx("span",{className:`w-1.5 h-1.5 rounded-full
          ${e.status===1?"bg-amber-500":e.status===2?"bg-green-600":"bg-red-600"}`}),r[e.status]]})]}),s.jsxs("span",{className:"text-xs text-gray-400",children:["Last updated: ",new Date(e.updated_at).toLocaleString()]})]}),s.jsx("hr",{className:"border-t border-gray-100"}),s.jsxs("div",{className:"px-5 py-4 bg-white",children:[s.jsx("p",{className:"text-xs text-gray-500 mb-3",children:"Take action on this rider's account"}),s.jsxs("div",{className:"flex gap-2.5 flex-wrap",children:[s.jsxs(N,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:2}),className:"inline-flex items-center gap-2 px-5 py-2.5 bg-green-700 hover:bg-green-800 text-green-50 text-sm font-medium rounded-lg transition-colors",children:[s.jsx("svg",{className:"w-3.5 h-3.5",viewBox:"0 0 16 16",fill:"none",children:s.jsx("path",{d:"M3 8.5L6.5 12L13 5",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round"})}),"Approve Rider"]}),s.jsxs(N,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:3}),className:"inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-red-50 text-red-700 border border-red-300 text-sm font-medium rounded-lg transition-colors",children:[s.jsx("svg",{className:"w-3.5 h-3.5",viewBox:"0 0 16 16",fill:"none",children:s.jsx("path",{d:"M4 4L12 12M12 4L4 12",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round"})}),"Reject"]})]})]})]})]})]})}export{_e as default};
