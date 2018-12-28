!function(u){u.widget("ui.tagit",{options:{allowDuplicates:!1,caseSensitive:!0,fieldName:"tags",placeholderText:null,readOnly:!1,removeConfirmation:!1,tagLimit:null,availableTags:[],autocomplete:{},showAutocompleteOnFocus:!1,allowSpaces:!1,singleField:!1,singleFieldDelimiter:",",singleFieldNode:null,animate:!0,tabIndex:null,beforeTagAdded:null,afterTagAdded:null,beforeTagRemoved:null,afterTagRemoved:null,onTagClicked:null,onTagLimitExceeded:null,onTagAdded:null,onTagRemoved:null,tagSource:null},_create:function(){var a=this;this.element.is("input")?(this.tagList=u("<ul></ul>").insertAfter(this.element),this.options.singleField=!0,this.options.singleFieldNode=this.element,this.element.addClass("tagit-hidden-field")):this.tagList=this.element.find("ul, ol").andSelf().last(),this.tagInput=u('<input type="text" />').addClass("ui-widget-content"),this.options.readOnly&&this.tagInput.attr("disabled","disabled"),this.options.tabIndex&&this.tagInput.attr("tabindex",this.options.tabIndex),this.options.placeholderText&&this.tagInput.attr("placeholder",this.options.placeholderText),this.options.autocomplete.source||(this.options.autocomplete.source=function(t,e){var i=t.term.toLowerCase(),a=u.grep(this.options.availableTags,function(t){return 0===t.toLowerCase().indexOf(i)});this.options.allowDuplicates||(a=this._subtractArray(a,this.assignedTags())),e(a)}),this.options.showAutocompleteOnFocus&&(this.tagInput.focus(function(t,e){a._showAutocomplete()}),void 0===this.options.autocomplete.minLength&&(this.options.autocomplete.minLength=0)),u.isFunction(this.options.autocomplete.source)&&(this.options.autocomplete.source=u.proxy(this.options.autocomplete.source,this)),u.isFunction(this.options.tagSource)&&(this.options.tagSource=u.proxy(this.options.tagSource,this)),this.tagList.addClass("tagit").addClass("ui-widget ui-widget-content ui-corner-all").append(u('<li class="tagit-new"></li>').append(this.tagInput)).click(function(t){var e=u(t.target);if(e.hasClass("tagit-label")){var i=e.closest(".tagit-choice");i.hasClass("removed")||a._trigger("onTagClicked",t,{tag:i,tagLabel:a.tagLabel(i)})}else a.tagInput.focus()});var i=!1;if(this.options.singleField)if(this.options.singleFieldNode){var t=u(this.options.singleFieldNode),e=t.val().split(this.options.singleFieldDelimiter);t.val(""),u.each(e,function(t,e){a.createTag(e,null,!0),i=!0})}else this.options.singleFieldNode=u('<input type="hidden" style="display:none;" value="" name="'+this.options.fieldName+'" />'),this.tagList.after(this.options.singleFieldNode);if(i||this.tagList.children("li").each(function(){u(this).hasClass("tagit-new")||(a.createTag(u(this).text(),u(this).attr("class"),!0),u(this).remove())}),this.tagInput.keydown(function(t){if(t.which==u.ui.keyCode.BACKSPACE&&""===a.tagInput.val()){var e=a._lastTag();!a.options.removeConfirmation||e.hasClass("remove")?a.removeTag(e):a.options.removeConfirmation&&e.addClass("remove ui-state-highlight")}else a.options.removeConfirmation&&a._lastTag().removeClass("remove ui-state-highlight");(t.which===u.ui.keyCode.COMMA&&!1===t.shiftKey||t.which===u.ui.keyCode.ENTER||t.which==u.ui.keyCode.TAB&&""!==a.tagInput.val()||t.which==u.ui.keyCode.SPACE&&!0!==a.options.allowSpaces&&('"'!=u.trim(a.tagInput.val()).replace(/^s*/,"").charAt(0)||'"'==u.trim(a.tagInput.val()).charAt(0)&&'"'==u.trim(a.tagInput.val()).charAt(u.trim(a.tagInput.val()).length-1)&&u.trim(a.tagInput.val()).length-1!=0))&&(t.which===u.ui.keyCode.ENTER&&""===a.tagInput.val()||t.preventDefault(),a.options.autocomplete.autoFocus&&a.tagInput.data("autocomplete-open")||(a.tagInput.autocomplete("close"),a.createTag(a._cleanedInput())))}).blur(function(t){a.tagInput.data("autocomplete-open")||a.createTag(a._cleanedInput())}),this.options.availableTags||this.options.tagSource||this.options.autocomplete.source){var s={select:function(t,e){return a.createTag(e.item.value),!1}};u.extend(s,this.options.autocomplete),s.source=this.options.tagSource||s.source,this.tagInput.autocomplete(s).bind("autocompleteopen.tagit",function(t,e){a.tagInput.data("autocomplete-open",!0)}).bind("autocompleteclose.tagit",function(t,e){a.tagInput.data("autocomplete-open",!1)}),this.tagInput.autocomplete("widget").addClass("tagit-autocomplete")}},destroy:function(){return u.Widget.prototype.destroy.call(this),this.element.unbind(".tagit"),this.tagList.unbind(".tagit"),this.tagInput.removeData("autocomplete-open"),this.tagList.removeClass(["tagit","ui-widget","ui-widget-content","ui-corner-all","tagit-hidden-field"].join(" ")),this.element.is("input")?(this.element.removeClass("tagit-hidden-field"),this.tagList.remove()):(this.element.children("li").each(function(){u(this).hasClass("tagit-new")?u(this).remove():(u(this).removeClass(["tagit-choice","ui-widget-content","ui-state-default","ui-state-highlight","ui-corner-all","remove","tagit-choice-editable","tagit-choice-read-only"].join(" ")),u(this).text(u(this).children(".tagit-label").text()))}),this.singleFieldNode&&this.singleFieldNode.remove()),this},_cleanedInput:function(){return u.trim(this.tagInput.val().replace(/^"(.*)"$/,"$1"))},_lastTag:function(){return this.tagList.find(".tagit-choice:last:not(.removed)")},_tags:function(){return this.tagList.find(".tagit-choice:not(.removed)")},assignedTags:function(){var t=this,e=[];return this.options.singleField?""===(e=u(this.options.singleFieldNode).val().split(this.options.singleFieldDelimiter))[0]&&(e=[]):this._tags().each(function(){e.push(t.tagLabel(this))}),e},_updateSingleTagsField:function(t){u(this.options.singleFieldNode).val(t.join(this.options.singleFieldDelimiter)).trigger("change")},_subtractArray:function(t,e){for(var i=[],a=0;a<t.length;a++)-1==u.inArray(t[a],e)&&i.push(t[a]);return i},tagLabel:function(t){return this.options.singleField?u(t).find(".tagit-label:first").text():u(t).find("input:first").val()},_showAutocomplete:function(){this.tagInput.autocomplete("search","")},_findTagByLabel:function(e){var i=this,a=null;return this._tags().each(function(t){if(i._formatStr(e)==i._formatStr(i.tagLabel(this)))return a=u(this),!1}),a},_isNew:function(t){return!this._findTagByLabel(t)},_formatStr:function(t){return this.options.caseSensitive?t:u.trim(t.toLowerCase())},_effectExists:function(t){return Boolean(u.effects&&(u.effects[t]||u.effects.effect&&u.effects.effect[t]))},createTag:function(t,e,i){var a=this;if(t=u.trim(t),this.options.preprocessTag&&(t=this.options.preprocessTag(t)),""===t)return!1;if(!this.options.allowDuplicates&&!this._isNew(t)){var s=this._findTagByLabel(t);return!1!==this._trigger("onTagExists",null,{existingTag:s,duringInitialization:i})&&this._effectExists("highlight")&&s.effect("highlight"),!1}if(this.options.tagLimit&&this._tags().length>=this.options.tagLimit)return this._trigger("onTagLimitExceeded",null,{duringInitialization:i}),!1;var o=u(this.options.onTagClicked?'<a class="tagit-label"></a>':'<span class="tagit-label"></span>').text(t),n=u("<li></li>").addClass("tagit-choice ui-widget-content ui-state-default ui-corner-all").addClass(e).append(o);if(this.options.readOnly)n.addClass("tagit-choice-read-only");else{n.addClass("tagit-choice-editable");var l=u("<span></span>").addClass("ui-icon ui-icon-close"),g=u('<a><span class="text-icon">×</span></a>').addClass("tagit-close").append(l).click(function(t){a.removeTag(n)});n.append(g)}if(!this.options.singleField){var r=o.html();n.append('<input type="hidden" value="'+r+'" name="'+this.options.fieldName+'" class="tagit-hidden-field" />')}if(!1!==this._trigger("beforeTagAdded",null,{tag:n,tagLabel:this.tagLabel(n),duringInitialization:i})){if(this.options.singleField){var h=this.assignedTags();h.push(t),this._updateSingleTagsField(h)}this._trigger("onTagAdded",null,n),this.tagInput.val(""),this.tagInput.parent().before(n),this._trigger("afterTagAdded",null,{tag:n,tagLabel:this.tagLabel(n),duringInitialization:i}),this.options.showAutocompleteOnFocus&&!i&&setTimeout(function(){a._showAutocomplete()},0)}},removeTag:function(t,e){if(e=void 0===e?this.options.animate:e,t=u(t),this._trigger("onTagRemoved",null,t),!1!==this._trigger("beforeTagRemoved",null,{tag:t,tagLabel:this.tagLabel(t)})){if(this.options.singleField){var i=this.assignedTags(),a=this.tagLabel(t);i=u.grep(i,function(t){return t!=a}),this._updateSingleTagsField(i)}if(e){t.addClass("removed");var s=this._effectExists("blind")?["blind",{direction:"horizontal"},"fast"]:["fast"],o=this;s.push(function(){t.remove(),o._trigger("afterTagRemoved",null,{tag:t,tagLabel:o.tagLabel(t)})}),t.fadeOut("fast").hide.apply(t,s).dequeue()}else t.remove(),this._trigger("afterTagRemoved",null,{tag:t,tagLabel:this.tagLabel(t)})}},removeTagByLabel:function(t,e){var i=this._findTagByLabel(t);if(!i)throw"No such tag exists with the name '"+t+"'";this.removeTag(i,e)},removeAll:function(){var i=this;this._tags().each(function(t,e){i.removeTag(e,!1)})}})}(jQuery);