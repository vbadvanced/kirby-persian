import jalaliday from 'jalaliday';

let dayjs = function(...args)  {

    // init dayjs jalali calendar
    if (typeof this.$library.dayjs.Ls.fa == 'undefined'){
        this.$library.dayjs.extend(jalaliday);
    } 

    let dayjs;
    if (args.length) {
        dayjs = this.$library.dayjs(...args);
    } else {
        dayjs = this.$library.dayjs();
    }

    return this.jalali ? dayjs.calendar('jalali') : dayjs;
}

let toBoolean = function(value){
    switch(String(value).toLowerCase().trim()){
        case "true": case "yes": case "1": return true;
        case "false": case "no": case "0": case null: return false;
        default: return false;
    }
}

export {
    dayjs,
    toBoolean
}