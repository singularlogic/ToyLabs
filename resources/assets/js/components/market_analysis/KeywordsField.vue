<template>
    <div class="ui fluid multiple search selection dropdown taginput" v-bind:class="{ readonly: readonly }" ref="tagInput">
        <input type="hidden" :value="keyphrases" ref="keyphrases">
        <div class="default text">{{ placeholderString }}</div>
    </div>
</template>

<script>

    export default {
        props: {
            value: Array,
            placeholder: String,
            readonly: Boolean
        },
        data() {
            return {
                keyphrases: '',
                placeholderString: this.placeholder || "Comma separated keywords. Prefix keyword with ! to exclude or ' for exact phrases."
            };
        },
        mounted() {
            if (this.value) {
                this.setValue(this.value);
            }

            var myComponent = this;
            function onKeyphrasesChange() {
                var keyphrases = [];
                if (myComponent.$refs.keyphrases.value != ""){
                    $.each(myComponent.$refs.keyphrases.value.split(','), function (index, keyphrase) {
                        var res = {value:'',type:0};
                        switch ((''+keyphrase).charAt(0)){
                            case '!':
                                res.value = (''+keyphrase).substr(1);
                                res.type = 2;
                                break;
                            case '\'':
                                res.value = (''+keyphrase).substr(1);
                                res.type = 1;
                                break;
                            default:
                                res.value = (''+keyphrase);
                                res.type = 0;
                                break
                        }
                        keyphrases.push(res);
                    });
                }
                myComponent.$emit('input', keyphrases);
            }

            $(document).ready(function() {
                $(myComponent.$refs.tagInput)
                    .dropdown({
                        allowAdditions: true,
                        onChange: function (e, t, n) {
//                            console.log('onChange', e, t, n, this);
                            onKeyphrasesChange();
                        },
                        addition: function(e) {
//                            console.log('addition', e, this);
                            return e
                        },
                        onAdd: function(e, t, n) {
//                            console.log('onAdd', e, t, n, this)
                        },
                        onRemove: function(e, t, n) {
//                            console.log('onRemove', e, t, n, this)
                        },
                        onLabelSelect: function(e) {
//                            console.log('onLabelSelect', e, this)
                        },
                        onLabelCreate: function(t, n) {
//                            console.log('onLabelCreate', t, n, this);
                            switch ((''+n).charAt(0)){
                                case '!':
                                    this.addClass('orange');
                                    this.html((''+n).substr(1) + '<i class="delete icon"></i>');
                                    this.attr('data-tooltip', 'None of: '+(''+n).substr(1));
                                    break;
                                case '\'':
//                            this.addClass('blue');
                                    this.addClass('green');
                                    this.html((''+n).substr(1) + '<i class="delete icon"></i>');
                                    this.attr('data-tooltip', 'Exact: '+(''+n).substr(1));
                                    break;
                                default:
//                            this.addClass('green');
                                    this.attr('data-tooltip', 'Any of: '+(''+n));
                                    break;
                            }
                            return $(this)
                        },
                        onLabelRemove: function(e) {
//                            console.log('onLabelRemove', e, this);
                            return !0
                        },
                        templates: {
                            label: function(e, t) {
//                                console.log('label', e, t, this);
                                return t + '<i class="delete icon"></i>'
                            },
                            message: function(e) {
//                                console.log('message', e, this);
                                return e
                            },
                            addition: function(e) {
//                                console.log('addition', e, this);
                                return e
                            }
                        }
                    });
                $('.form').keydown(function(event){
                    if( (event.keyCode == 13) && $(event.target).hasClass('search') ) {
                        event.preventDefault();
                        return false;
                    }
                });
            });
        },
        methods: {
            setValue (data) {
                var keyphrases = '';
                $.each(data, function( index, keyphrase ) {
                    switch (keyphrase.type){
                        case 1:
                            keyphrases+=((keyphrases.length)?',':'')+'\''+keyphrase.value;
                            break;
                        case 2:
                            keyphrases+=((keyphrases.length)?',':'')+'!'+keyphrase.value;
                            break;
                        case 0:
                        default:
                            keyphrases+=((keyphrases.length)?',':'')+keyphrase.value;
                            break;
                    }
                });
                this.keyphrases = keyphrases;
            }
        }
    }
</script>

<style>
   .taginput.readonly {
       pointer-events: inherit !important;
   }

   .taginput.readonly i {
       display: none !important;
   }

   .taginput.readonly .search {
       display: none !important;
   }
   .taginput.readonly.dropdown, .taginput.readonly.dropdown>input.search {
       cursor: inherit !important;
   }
   .taginput.readonly.ui.selection.dropdown {
       background-color: inherit;
   }
</style>