var fluid_1_4=fluid_1_4||{};(function(fluid){fluid.registerNamespace("fluid.compat.fluid_1_3.uploader");fluid.staticEnvironment.uploader_1_3_Compatibility=fluid.typeTag("fluid.uploader.fluid_1_3");fluid.compat.fluid_1_3.uploader.fileTypeTransformer=function(model,expandSpec){var mimeTypeMap=fluid.uploader.mimeTypeRegistry;var val=fluid.get(model,expandSpec.path);if(fluid.isArrayable(val)||typeof (val)!=="string"){return val}var exts=val.split(";");if(exts.length===0){return undefined}var mimeTypes=[];fluid.each(exts,function(ext){ext=ext.substring(2);var mimeType=mimeTypeMap[ext];if(mimeType){mimeTypes.push(mimeType)}});return mimeTypes};fluid.compat.fluid_1_3.uploader.optionsRules={gradeNames:"gradeNames",components:"components",invokers:"invokers",queueSettings:"queueSettings",demo:"demo",selectors:"selectors",focusWithEvent:"focusWithEvent",styles:"styles",events:"events",listeners:"listeners",strings:"strings",mergePolicy:"mergePolicy","queueSettings.fileTypes":{expander:{type:"fluid.compat.fluid_1_3.uploader.fileTypeTransformer",path:"queueSettings.fileTypes"}}};fluid.demands("fluid.uploader","fluid.uploader.fluid_1_3",{mergeOptions:{transformOptions:{transformer:"fluid.model.transformWithRules",config:fluid.compat.fluid_1_3.uploader.optionsRules}}});fluid.demands("fluid.uploader",["fluid.uploader.fluid_1_2","fluid.uploader.fluid_1_3"],{mergeOptions:{transformOptions:{transformer:"fluid.model.transformWithRules",config:[fluid.compat.fluid_1_2.uploader.optionsRules,fluid.compat.fluid_1_3.uploader.optionsRules]}}});fluid.uploader.transformOptions=function(options){if(!options){return }var rules=typeof (fluid.compat.fluid_1_2.uploader)!=="undefined"?[fluid.compat.fluid_1_2.uploader.optionsRules,fluid.compat.fluid_1_3.uploader.optionsRules]:fluid.compat.fluid_1_3.uploader.optionsRules;options.transformOptions={transformer:"fluid.model.transformWithRules",config:rules};return options}})(fluid_1_4);