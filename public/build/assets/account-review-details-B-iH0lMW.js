import{r as m,K as O,j as o,L as F,$}from"./app-DwkgUtw6.js";import{A as P}from"./app-layout-CEC_JvlO.js";/* empty css            */import"./app-logo-icon-BqSpMv_a.js";import"./index-BgU571X2.js";import"./index-L_o0srgB.js";let C={data:""},M=e=>typeof window=="object"?((e?e.querySelector("#_goober"):window._goober)||Object.assign((e||document.head).appendChild(document.createElement("style")),{innerHTML:" ",id:"_goober"})).firstChild:e||C,T=/(?:([\u0080-\uFFFF\w-%@]+) *:? *([^{;]+?);|([^;}{]*?) *{)|(}\s*)/g,B=/\/\*[^]*?\*\/|  +/g,A=/\n+/g,g=(e,t)=>{let r="",s="",n="";for(let i in e){let a=e[i];i[0]=="@"?i[1]=="i"?r=i+" "+a+";":s+=i[1]=="f"?g(a,i):i+"{"+g(a,i[1]=="k"?"":t)+"}":typeof a=="object"?s+=g(a,t?t.replace(/([^,])+/g,d=>i.replace(/([^,]*:\S+\([^)]*\))|([^,])+/g,l=>/&/.test(l)?l.replace(/&/g,d):d?d+" "+l:l)):i):a!=null&&(i=/^--/.test(i)?i:i.replace(/[A-Z]/g,"-$&").toLowerCase(),n+=g.p?g.p(i,a):i+":"+a+";")}return r+(t&&n?t+"{"+n+"}":n)+s},f={},R=e=>{if(typeof e=="object"){let t="";for(let r in e)t+=r+R(e[r]);return t}return e},H=(e,t,r,s,n)=>{let i=R(e),a=f[i]||(f[i]=(l=>{let p=0,u=11;for(;p<l.length;)u=101*u+l.charCodeAt(p++)>>>0;return"go"+u})(i));if(!f[a]){let l=i!==e?e:(p=>{let u,b,y=[{}];for(;u=T.exec(p.replace(B,""));)u[4]?y.shift():u[3]?(b=u[3].replace(A," ").trim(),y.unshift(y[0][b]=y[0][b]||{})):y[0][u[1]]=u[2].replace(A," ").trim();return y[0]})(e);f[a]=g(n?{["@keyframes "+a]:l}:l,r?"":"."+a)}let d=r&&f.g?f.g:null;return r&&(f.g=f[a]),((l,p,u,b)=>{b?p.data=p.data.replace(b,l):p.data.indexOf(l)===-1&&(p.data=u?l+p.data:p.data+l)})(f[a],t,s,d),a},Z=(e,t,r)=>e.reduce((s,n,i)=>{let a=t[i];if(a&&a.call){let d=a(r),l=d&&d.props&&d.props.className||/^go/.test(d)&&d;a=l?"."+l:d&&typeof d=="object"?d.props?"":g(d,""):d===!1?"":d}return s+n+(a??"")},"");function w(e){let t=this||{},r=e.call?e(t.p):e;return H(r.unshift?r.raw?Z(r,[].slice.call(arguments,1),t.p):r.reduce((s,n)=>Object.assign(s,n&&n.call?n(t.p):n),{}):r,M(t.target),t.g,t.o,t.k)}let z,N,E;w.bind({g:1});let h=w.bind({k:1});function q(e,t,r,s){g.p=t,z=e,N=r,E=s}function x(e,t){let r=this||{};return function(){let s=arguments;function n(i,a){let d=Object.assign({},i),l=d.className||n.className;r.p=Object.assign({theme:N&&N()},d),r.o=/ *go\d+/.test(l),d.className=w.apply(r,s)+(l?" "+l:"");let p=e;return e[0]&&(p=d.as||e,delete d.as),E&&p[0]&&E(d),z(p,d)}return n}}var G=e=>typeof e=="function",k=(e,t)=>G(e)?e(t):e,K=(()=>{let e=0;return()=>(++e).toString()})(),Q=(()=>{let e;return()=>{if(e===void 0&&typeof window<"u"){let t=matchMedia("(prefers-reduced-motion: reduce)");e=!t||t.matches}return e}})(),W=20,D="default",S=(e,t)=>{let{toastLimit:r}=e.settings;switch(t.type){case 0:return{...e,toasts:[t.toast,...e.toasts].slice(0,r)};case 1:return{...e,toasts:e.toasts.map(a=>a.id===t.toast.id?{...a,...t.toast}:a)};case 2:let{toast:s}=t;return S(e,{type:e.toasts.find(a=>a.id===s.id)?1:0,toast:s});case 3:let{toastId:n}=t;return{...e,toasts:e.toasts.map(a=>a.id===n||n===void 0?{...a,dismissed:!0,visible:!1}:a)};case 4:return t.toastId===void 0?{...e,toasts:[]}:{...e,toasts:e.toasts.filter(a=>a.id!==t.toastId)};case 5:return{...e,pausedAt:t.time};case 6:let i=t.time-(e.pausedAt||0);return{...e,pausedAt:void 0,toasts:e.toasts.map(a=>({...a,pauseDuration:a.pauseDuration+i}))}}},Y=[],J={toasts:[],pausedAt:void 0,settings:{toastLimit:W}},v={},I=(e,t=D)=>{v[t]=S(v[t]||J,e),Y.forEach(([r,s])=>{r===t&&s(v[t])})},L=e=>Object.keys(v).forEach(t=>I(e,t)),U=e=>Object.keys(v).find(t=>v[t].toasts.some(r=>r.id===e)),_=(e=D)=>t=>{I(t,e)},V=(e,t="blank",r)=>({createdAt:Date.now(),visible:!0,dismissed:!1,type:t,ariaProps:{role:"status","aria-live":"polite"},message:e,pauseDuration:0,...r,id:(r==null?void 0:r.id)||K()}),j=e=>(t,r)=>{let s=V(t,e,r);return _(s.toasterId||U(s.id))({type:2,toast:s}),s.id},c=(e,t)=>j("blank")(e,t);c.error=j("error");c.success=j("success");c.loading=j("loading");c.custom=j("custom");c.dismiss=(e,t)=>{let r={type:3,toastId:e};t?_(t)(r):L(r)};c.dismissAll=e=>c.dismiss(void 0,e);c.remove=(e,t)=>{let r={type:4,toastId:e};t?_(t)(r):L(r)};c.removeAll=e=>c.remove(void 0,e);c.promise=(e,t,r)=>{let s=c.loading(t.loading,{...r,...r==null?void 0:r.loading});return typeof e=="function"&&(e=e()),e.then(n=>{let i=t.success?k(t.success,n):void 0;return i?c.success(i,{id:s,...r,...r==null?void 0:r.success}):c.dismiss(s),n}).catch(n=>{let i=t.error?k(t.error,n):void 0;i?c.error(i,{id:s,...r,...r==null?void 0:r.error}):c.dismiss(s)}),e};var X=h`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
 transform: scale(1) rotate(45deg);
  opacity: 1;
}`,ee=h`
from {
  transform: scale(0);
  opacity: 0;
}
to {
  transform: scale(1);
  opacity: 1;
}`,te=h`
from {
  transform: scale(0) rotate(90deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(90deg);
	opacity: 1;
}`,re=x("div")`
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
`,se=h`
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
`,ae=x("div")`
  width: 12px;
  height: 12px;
  box-sizing: border-box;
  border: 2px solid;
  border-radius: 100%;
  border-color: ${e=>e.secondary||"#e0e0e0"};
  border-right-color: ${e=>e.primary||"#616161"};
  animation: ${se} 1s linear infinite;
`,ie=h`
from {
  transform: scale(0) rotate(45deg);
	opacity: 0;
}
to {
  transform: scale(1) rotate(45deg);
	opacity: 1;
}`,oe=h`
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
}`,ne=x("div")`
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
`,de=x("div")`
  position: absolute;
`,le=x("div")`
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  min-width: 20px;
  min-height: 20px;
`,ce=h`
from {
  transform: scale(0.6);
  opacity: 0.4;
}
to {
  transform: scale(1);
  opacity: 1;
}`,pe=x("div")`
  position: relative;
  transform: scale(0.6);
  opacity: 0.4;
  min-width: 20px;
  animation: ${ce} 0.3s 0.12s cubic-bezier(0.175, 0.885, 0.32, 1.275)
    forwards;
`,me=({toast:e})=>{let{icon:t,type:r,iconTheme:s}=e;return t!==void 0?typeof t=="string"?m.createElement(pe,null,t):t:r==="blank"?null:m.createElement(le,null,m.createElement(ae,{...s}),r!=="loading"&&m.createElement(de,null,r==="error"?m.createElement(re,{...s}):m.createElement(ne,{...s})))},ue=e=>`
0% {transform: translate3d(0,${e*-200}%,0) scale(.6); opacity:.5;}
100% {transform: translate3d(0,0,0) scale(1); opacity:1;}
`,fe=e=>`
0% {transform: translate3d(0,0,-1px) scale(1); opacity:1;}
100% {transform: translate3d(0,${e*-150}%,-1px) scale(.6); opacity:0;}
`,he="0%{opacity:0;} 100%{opacity:1;}",ge="0%{opacity:1;} 100%{opacity:0;}",xe=x("div")`
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
`,be=x("div")`
  display: flex;
  justify-content: center;
  margin: 4px 10px;
  color: inherit;
  flex: 1 1 auto;
  white-space: pre-line;
`,ye=(e,t)=>{let r=e.includes("top")?1:-1,[s,n]=Q()?[he,ge]:[ue(r),fe(r)];return{animation:t?`${h(s)} 0.35s cubic-bezier(.21,1.02,.73,1) forwards`:`${h(n)} 0.4s forwards cubic-bezier(.06,.71,.55,1)`}};m.memo(({toast:e,position:t,style:r,children:s})=>{let n=e.height?ye(e.position||t||"top-center",e.visible):{opacity:0},i=m.createElement(me,{toast:e}),a=m.createElement(be,{...e.ariaProps},k(e.message,e));return m.createElement(xe,{className:e.className,style:{...n,...r,...e.style}},typeof s=="function"?s({icon:i,message:a}):m.createElement(m.Fragment,null,i,a))});q(m.createElement);w`
  z-index: 9999;
  > * {
    pointer-events: auto;
  }
`;var ve=c;function _e(){const{rider:e,toastMessage:t}=O().props,r={1:"Pending",2:"Approved",3:"Rejected"};return m.useEffect(()=>{t&&ve.success(t)},[t]),o.jsxs(P,{children:[o.jsx(F,{title:`Rider Review - ${e.name}`}),o.jsxs("div",{className:"w-full p-4 bg-white shadow rounded",children:[o.jsx("div",{className:"mb-6",children:o.jsx($,{href:"/riders",className:"text-blue-500 hover:underline",children:"← Back to Riders"})}),o.jsxs("div",{className:"border p-4 rounded-lg shadow-sm mb-6",children:[o.jsx("h1",{className:"text-2xl font-bold mb-4",children:"Rider Information"}),o.jsxs("div",{className:"grid grid-cols-1 md:grid-cols-2 gap-6",children:[o.jsxs("div",{children:[o.jsxs("p",{children:[o.jsx("strong",{children:"Name:"})," ",e.name]}),o.jsxs("p",{children:[o.jsx("strong",{children:"Email:"})," ",e.email]}),o.jsxs("p",{children:[o.jsx("strong",{children:"Phone:"})," ",e.phone_number]}),o.jsxs("p",{children:[o.jsx("strong",{children:"Gender:"})," ",e.gender||"N/A"]}),o.jsxs("p",{children:[o.jsx("strong",{children:"Date of Birth:"})," ",e.dob||"N/A"]})]}),o.jsx("div",{className:"flex items-center",children:e.profile_image?o.jsx("img",{src:e.profile_image,alt:e.name,className:"w-32 h-32 rounded-full object-cover border"}):o.jsx("p",{children:"No profile image"})})]})]}),o.jsxs("div",{children:[o.jsx("h2",{className:"text-xl font-semibold mb-4",children:"Submitted Documents"}),e.user_riders.length>0?e.user_riders.map(s=>o.jsxs("div",{className:"border p-4 rounded-lg shadow-sm mb-6",children:[o.jsxs("p",{children:[o.jsx("strong",{children:"Document Type:"})," ",s.document_type]}),o.jsxs("p",{children:[o.jsx("strong",{children:"Document Number:"})," ",s.document_number]}),o.jsxs("p",{children:[o.jsx("strong",{children:"Review Status:"})," ",r[s.review_status]]}),s.remarks&&o.jsxs("p",{children:[o.jsx("strong",{children:"Remarks:"})," ",s.remarks]}),o.jsx("div",{className:"grid grid-cols-1 md:grid-cols-3 gap-4 mt-4",children:s.document.map((n,i)=>o.jsx("img",{src:n,alt:`${s.document_type} ${i+1}`,className:"w-full h-48 object-cover rounded border"},i))})]},s.id)):o.jsx("p",{children:"No documents submitted"})]}),o.jsxs("div",{className:"mt-6 flex gap-4",children:[o.jsx($,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:2}),className:"px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600",children:"Approve"}),o.jsx($,{href:route("riders.rider.account.approve",{user_id:e.id,status_type:3}),className:"px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600",children:"Reject"})]})]})]})}export{_e as default};
