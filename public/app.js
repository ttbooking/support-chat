"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[260],{111:(e,n,t)=>{var s=t(538),r=t(381),o=t.n(r);const a={computed:{SupportChat:function(e){function n(){return e.apply(this,arguments)}return n.toString=function(){return e.toString()},n}((function(){return SupportChat}))},methods:{formatTime:function(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"DD.MM.YYYY HH:mm:ss";return o()(e).format(n)}}};var i=t(554),c=t(757),u=t.n(c),d=t(629),m="SET_USER_ID",f="SET_ROOMS_LOADED_STATE",l="SET_ROOMS",g="ADD_ROOM",p="DELETE_ROOM",I="SET_ROOM_ID",h="ROOM_SET_USERS",v="ROOM_JOIN_USER",x="ROOM_LEAVE_USER",w="SET_MESSAGES",y="SET_NEXT_MSGID",M="INC_NEXT_MSGID",S="INIT_MESSAGE",O="ADD_MESSAGE",b="UPDATE_MESSAGE",E="EDIT_MESSAGE",_="FAIL_MESSAGE",R="DELETE_MESSAGE",A="UPLOAD_PROGRESS",j="LEAVE_REACTION",D="REMOVE_REACTION",P=window.SupportChat.path+"/api/v1";const T={index:function(){return axios.get(P+"/rooms")},store:function(e){return axios.post(P+"/rooms",e)},show:function(e){return axios.get(P+"/rooms/"+e)},update:function(e,n){return axios.put(P+"/rooms/"+e,n)},destroy:function(e){return axios.delete(P+"/rooms/"+e)}};var k=window.SupportChat.path+"/api/v1";const C={index:function(e){return axios.get("".concat(k,"/rooms/").concat(e,"/messages"))},store:function(e,n){return axios.post("".concat(k,"/rooms/").concat(e,"/messages"),n)},show:function(e){return axios.get("".concat(k,"/messages/").concat(e))},update:function(e,n){return axios.put("".concat(k,"/messages/").concat(e),n)},destroy:function(e){return axios.delete("".concat(k,"/messages/").concat(e))}};var U=window.SupportChat.path+"/api/v1";const L={store:function(e,n){return axios.post("".concat(U,"/messages/").concat(e,"/reactions"),n,{headers:{"Content-Type":"text/plain"}})},destroy:function(e,n){return axios.delete("".concat(U,"/messages/").concat(e,"/reactions/").concat(n))}};var G=window.SupportChat.path+"/api/v1";const Z={rooms:T,messages:C,messageReactions:L,attachments:{store:function(e,n,t){return axios.post("".concat(G,"/messages/").concat(e,"/attachments"),n,t)},show:function(e,n){return axios.get("".concat(G,"/messages/").concat(e,"/attachments/").concat(n))},destroy:function(e,n){return axios.delete("".concat(G,"/messages/").concat(e,"/attachments/").concat(n))}}};var B;function N(e,n,t,s,r,o,a){try{var i=e[o](a),c=i.value}catch(e){return void t(e)}i.done?n(c):Promise.resolve(c).then(s,r)}function F(e){return function(){var n=this,t=arguments;return new Promise((function(s,r){var o=e.apply(n,t);function a(e){N(o,s,r,a,i,"next",e)}function i(e){N(o,s,r,a,i,"throw",e)}a(void 0)}))}}function H(e,n){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);n&&(s=s.filter((function(n){return Object.getOwnPropertyDescriptor(e,n).enumerable}))),t.push.apply(t,s)}return t}function X(e){for(var n=1;n<arguments.length;n++){var t=null!=arguments[n]?arguments[n]:{};n%2?H(Object(t),!0).forEach((function(n){z(e,n,t[n])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):H(Object(t)).forEach((function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(t,n))}))}return e}function z(e,n,t){return n in e?Object.defineProperty(e,n,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[n]=t,e}function Y(e,n){var t="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!t){if(Array.isArray(e)||(t=V(e))||n&&e&&"number"==typeof e.length){t&&(e=t);var s=0,r=function(){};return{s:r,n:function(){return s>=e.length?{done:!0}:{done:!1,value:e[s++]}},e:function(e){throw e},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var o,a=!0,i=!1;return{s:function(){t=t.call(e)},n:function(){var e=t.next();return a=e.done,e},e:function(e){i=!0,o=e},f:function(){try{a||null==t.return||t.return()}finally{if(i)throw o}}}}function q(e){return function(e){if(Array.isArray(e))return W(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||V(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function V(e,n){if(e){if("string"==typeof e)return W(e,n);var t=Object.prototype.toString.call(e).slice(8,-1);return"Object"===t&&e.constructor&&(t=e.constructor.name),"Map"===t||"Set"===t?Array.from(e):"Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t)?W(e,n):void 0}}function W(e,n){(null==n||n>e.length)&&(n=e.length);for(var t=0,s=new Array(n);t<n;t++)s[t]=e[t];return s}s.Z.use(d.ZP);const $=new d.ZP.Store({state:{currentUserId:null,rooms:[],roomsLoaded:!1,roomId:null,nextMessageId:1,messages:[]},getters:{findMessageIndexById:function(e){return function(n){return e.messages.findIndex((function(e){return e._id===n}))}},findMessageIndexByIndexId:function(e){return function(n){return e.messages.findIndex((function(e){return e.indexId===n}))}},findMessageIndex:function(e,n){return function(e){return null!==e.indexId?n.findMessageIndexByIndexId(e.indexId):n.findMessageIndexById(e._id)}},getMessage:function(e){return function(n){var t=e.messages.findIndex((function(e){return e._id===n}));return e.messages[t]}},joinedRooms:function(e){return e.rooms.filter((function(n){return n.users.some((function(n){return n._id===e.currentUserId}))}))}},mutations:(B={},z(B,m,(function(e,n){e.currentUserId=+n})),z(B,f,(function(e,n){e.roomsLoaded=n})),z(B,l,(function(e,n){e.rooms=n})),z(B,g,(function(e,n){e.rooms=[].concat(q(e.rooms),[n])})),z(B,p,(function(e,n){e.rooms=e.rooms.filter((function(e){return e.roomId!==n}))})),z(B,I,(function(e,n){e.roomId=n})),z(B,h,(function(e,n){var t=n.roomId,s=n.users,r=e.rooms.findIndex((function(e){return e.roomId===t}));e.rooms[r].users=s,e.rooms=q(e.rooms)})),z(B,v,(function(e,n){var t=n.roomId,s=n.user,r=e.rooms.findIndex((function(e){return e.roomId===t}));e.rooms[r].users=[].concat(q(e.rooms[r].users),[s]),e.rooms=q(e.rooms)})),z(B,x,(function(e,n){var t=n.roomId,s=n.user,r=e.rooms.findIndex((function(e){return e.roomId===t})),o=e.rooms[r].users.filter((function(e){return e._id!==s._id}));e.rooms[r].users=q(o),e.rooms=q(e.rooms)})),z(B,w,(function(e,n){e.messages=n})),z(B,y,(function(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,t=Math.max.apply(Math,[0].concat(q(e.messages.map((function(e){return e._id})))));e.nextMessageId=null!=n?n:t+1})),z(B,M,(function(e){e.nextMessageId++})),z(B,S,(function(e,n){var t,s=n._id,r=n.content,o=n.replyMessage,a=n.files,i={_id:s,content:r,senderId:e.currentUserId,username:"",system:!1,saved:!1,distributed:!1,seen:!1,deleted:!1,failure:!1,disableActions:!1,disableReactions:!1,files:[],reactions:{},replyMessage:o},c=Y(a||[]);try{for(c.s();!(t=c.n()).done;){var u=t.value;i.files.push({name:u.name,size:u.size,type:u.extension,audio:!1,duration:0,url:u.localURL,preview:null,progress:0})}}catch(e){c.e(e)}finally{c.f()}e.messages=[].concat(q(e.messages),[i])})),z(B,O,(function(e,n){e.messages=[].concat(q(e.messages),[n])})),z(B,b,(function(e,n){var t=n.messageIndex,s=n.message;e.messages[t]=s,e.messages=q(e.messages)})),z(B,E,(function(e,n){var t=n.messageIndex,s=n.message;e.messages[t]=s,e.messages=q(e.messages)})),z(B,_,(function(e,n){e.messages[n]=X(X({},e.messages[n]),{},{failure:!0}),e.messages=q(e.messages)})),z(B,R,(function(e,n){e.messages[n]=X(X({},e.messages[n]),{},{deleted:!0}),e.messages=q(e.messages)})),z(B,A,(function(e,n){var t=n.messageIndex,s=n.filename,r=n.progress,o=e.messages[t].files.findIndex((function(e){return e.name+"."+e.type===s}));e.messages[t].files[o].progress=r})),z(B,j,(function(e,n){var t,r=n.userId,o=n.messageId,a=n.reaction,i=getters.findMessageIndexByIndexId(o),c=null!==(t=e.messages[i].reactions[a])&&void 0!==t?t:[];c.push(r),s.Z.set(e.messages[i].reactions,a,q(new Set(c)))})),z(B,D,(function(e,n){var t,r=n.userId,o=n.messageId,a=n.reaction,i=getters.findMessageIndexByIndexId(o),c=null!==(t=e.messages[i].reactions[a])&&void 0!==t?t:[],u=c.indexOf(r);u>-1&&c.splice(u,1),s.Z.set(e.messages[i].reactions,a,q(new Set(c)))})),B),actions:{fetchRooms:function(e){return F(u().mark((function n(){var t,s,r,o,a,i,c;return u().wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return t=e.commit,s=e.state,r=e.getters,t(f,!1),n.next=4,Z.rooms.index();case 4:o=n.sent,t(l,o.data.data),t(f,!0),a=Y(r.joinedRooms);try{for(c=function(){var e=i.value;window.roomChannel=window.Echo.join("support-chat.room.".concat(e.roomId)).here((function(n){t(h,{roomId:e.roomId,users:n})})).joining((function(n){t(v,{roomId:e.roomId,user:n})})).leaving((function(n){t(x,{roomId:e.roomId,user:n})})).error((function(e){})).listen(".message.posted",(function(n){e.roomId===s.roomId&&t(O,n),t(y)})).listen(".message.edited",(function(n){var o=r.findMessageIndex(n);e.roomId===s.roomId&&t(E,{messageIndex:o,message:n})})).listen(".message.deleted",(function(n){var o=r.findMessageIndex(n);e.roomId===s.roomId&&t(R,o)})).listen(".message-reaction.left",(function(e){t(j,{userId:e.user_id,messageId:e.message_id,reaction:e.emoji})})).listen(".message-reaction.removed",(function(e){t(D,{userId:e.user_id,messageId:e.message_id,reaction:e.emoji})})).listenForWhisper(".upload.progress",(function(e){var n=e.messageIndexId,s=e.filename,o=e.progress,a=r.findMessageIndexByIndexId(n);a>-1&&t(A,{messageIndex:a,filename:s,progress:o})})).listen(".message-attachment.upload-finished",(function(e){var n=r.findMessageIndexByIndexId(e.message_id);t(A,{messageIndex:n,filename:e.filename,progress:-1})}))},a.s();!(i=a.n()).done;)c()}catch(e){a.e(e)}finally{a.f()}case 9:case"end":return n.stop()}}),n)})))()},addRoom:function(e){var n=arguments;return F(u().mark((function t(){var s,r,o;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,r=n.length>1&&void 0!==n[1]?n[1]:{},t.next=4,Z.rooms.store(r);case 4:o=t.sent,s(g,o.data.data);case 6:case"end":return t.stop()}}),t)})))()},deleteRoom:function(e,n){return F(u().mark((function t(){var s;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,t.next=3,Z.rooms.destroy(n);case 3:t.sent,s(p,n);case 5:case"end":return t.stop()}}),t)})))()},fetchMessages:function(e,n){return F(u().mark((function t(){var s,r,o;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,e.state,r=n.room,n.options,s(I,r.roomId),t.next=5,Z.messages.index(r.roomId);case 5:o=t.sent,s(w,o.data.data),s(y);case 8:case"end":return t.stop()}}),t)})))()},sendMessage:function(e,n){return F(u().mark((function t(){var s,r,o,a,i,c,d,m,f;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,r=e.state,o=e.dispatch,a=n.roomId,i=n.content,c=n.files,d=n.replyMessage,n.usersTag,m=r.nextMessageId,s(M),s(S,f={_id:m,content:i,replyMessage:d,files:c}),t.next=8,o("trySendMessage",{roomId:a,message:f});case 8:case"end":return t.stop()}}),t)})))()},trySendMessage:function(e,n){return F(u().mark((function t(){var s,r,o,a,i,c,d,m,f,l,g,p,I,h;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,r=e.dispatch,o=e.getters,a=n.roomId,i=n.message,c=o.findMessageIndex(i),t.prev=3,t.next=6,Z.messages.store(a,{content:i.content,parent_id:null===(d=i.replyMessage)||void 0===d?void 0:d.indexId,attachments:null!==(m=null===(f=i.files)||void 0===f?void 0:f.map((function(e){return{name:e.name+"."+e.extension,type:e.type,size:e.size}})))&&void 0!==m?m:[]});case 6:l=t.sent,g=l.data.data,s(b,{messageIndex:c,message:X(X({},g),{},{_id:i._id})}),p=Y(i.files||[]),t.prev=10,p.s();case 12:if((I=p.n()).done){t.next=19;break}return h=I.value,t.next=16,r("uploadAttachment",{message:g,file:h});case 16:s(A,{messageIndex:c,filename:h.name+"."+h.extension,progress:-1});case 17:t.next=12;break;case 19:t.next=24;break;case 21:t.prev=21,t.t0=t.catch(10),p.e(t.t0);case 24:return t.prev=24,p.f(),t.finish(24);case 27:t.next=32;break;case 29:t.prev=29,t.t1=t.catch(3),s(_,c);case 32:case"end":return t.stop()}}),t,null,[[3,29],[10,21,24,27]])})))()},syncAttachments:function(e,n){return F(u().mark((function t(){return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:e.commit,e.state,n.messageId,n.files;case 2:case"end":return t.stop()}}),t)})))()},uploadAttachment:function(e,n){return F(u().mark((function t(){var s,r,o,a,i,c;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,r=e.getters,o=n.message,a=n.file,(i=new FormData).append("attachment",a.blob,a.name+"."+a.extension),c=r.findMessageIndex(o),t.next=7,Z.attachments.store(o.indexId,i,{onUploadProgress:function(e){var n={messageIndexId:o.indexId,filename:a.name+"."+a.extension,progress:Math.round(100*e.loaded/e.total)};window.roomChannel.whisper(".upload.progress",n),s(A,{messageIndex:c,filename:n.filename,progress:n.progress})}});case 7:t.sent;case 8:case"end":return t.stop()}}),t)})))()},editMessage:function(e,n){return F(u().mark((function t(){var s,r,o,a,i,c,d,m;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,e.state,r=e.getters,n.roomId,o=n.messageId,a=n.newContent,n.files,i=n.replyMessage,n.usersTag,t.next=4,Z.messages.update(o,{content:a,replyMessage:null!=i?i:r.getMessage(o).replyMessage});case 4:c=t.sent,d=c.data.data,m=r.findMessageIndex(d),s(E,{messageIndex:m,savedMessage:d});case 8:case"end":return t.stop()}}),t)})))()},deleteMessage:function(e,n){return F(u().mark((function t(){var s,r,o,a;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,r=e.getters,n.roomId,o=n.message,t.next=4,Z.messages.destroy(o.indexId);case 4:t.sent,a=r.findMessageIndex(o),s(R,a);case 7:case"end":return t.stop()}}),t)})))()},sendMessageReaction:function(e,n){return F(u().mark((function t(){var s,r,o,a,i;return u().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return s=e.commit,r=e.state,n.roomId,o=n.messageId,a=n.reaction,i=n.remove,t.next=4,Z.messageReactions[i?"destroy":"store"](o,a.unicode);case 4:t.sent,s(i?D:j,{userId:r.currentUserId,messageId:o,reaction:a.unicode});case 6:case"end":return t.stop()}}),t)})))()}}});var J=t(656),K=t.n(J);t(492);function Q(e,n){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);n&&(s=s.filter((function(n){return Object.getOwnPropertyDescriptor(e,n).enumerable}))),t.push.apply(t,s)}return t}function ee(e){for(var n=1;n<arguments.length;n++){var t=null!=arguments[n]?arguments[n]:{};n%2?Q(Object(t),!0).forEach((function(n){ne(e,n,t[n])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):Q(Object(t)).forEach((function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(t,n))}))}return e}function ne(e,n,t){return n in e?Object.defineProperty(e,n,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[n]=t,e}const te={components:{ChatWindow:K()},props:["userId"],data:function(){return{menuActions:[{name:"deleteRoom",title:"Delete Room"}]}},mounted:function(){this.setUser(this.userId),this.fetchRooms()},methods:ee(ee({openFile:function(e){e.message;var n=e.file;window.location=n.file.url},menuActionHandler:function(e){var n=e.roomId;if("deleteRoom"===e.action.name)this.deleteRoom(n)}},(0,d.OI)({setUser:m})),(0,d.nv)(["fetchRooms","addRoom","deleteRoom","fetchMessages","sendMessage","trySendMessage","editMessage","deleteMessage","sendMessageReaction"])),computed:(0,d.rn)(["currentUserId","rooms","roomsLoaded","messages"])};const se=(0,t(900).Z)(te,(function(){var e=this,n=e.$createElement,t=e._self._c||n;return e.currentUserId?t("chat-window",{attrs:{"current-user-id":e.currentUserId,rooms:e.rooms,"rooms-loaded":e.roomsLoaded,messages:e.messages,"messages-loaded":!0,"room-actions":e.menuActions,"menu-actions":e.menuActions},on:{"fetch-messages":e.fetchMessages,"fetch-more-rooms":e.fetchRooms,"send-message":e.sendMessage,"edit-message":e.editMessage,"delete-message":e.deleteMessage,"open-file":e.openFile,"open-failed-message":e.trySendMessage,"add-room":e.addRoom,"room-action-handler":e.menuActionHandler,"menu-action-handler":e.menuActionHandler,"send-message-reaction":e.sendMessageReaction}}):e._e()}),[],!1,null,null,null).exports;var re,oe;window.axios=t(669),window.axios.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";var ae=document.head.querySelector('meta[name="csrf-token"]');ae&&(window.axios.defaults.headers.common["X-CSRF-TOKEN"]=ae.content),window.Pusher=t(606),window.Echo=new i.Z({broadcaster:"pusher",key:window.SupportChat.pusher.key,cluster:null!==(re=window.SupportChat.pusher.cluster)&&void 0!==re?re:"eu",forceTLS:null===(oe=window.SupportChat.pusher.useTLS)||void 0===oe||oe}),s.Z.mixin(a),new s.Z({el:"#support-chat",components:{SupportChat:se},store:$})},347:()=>{}},e=>{var n=n=>e(e.s=n);e.O(0,[143,660],(()=>(n(111),n(347))));e.O()}]);