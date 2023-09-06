"use strict";

console.log('loading... xp-admin-jobs-v2.js');

let data = {
    index_company: 3,
    level_min: -1,
    tree_data: [
        {
            id: 1,
            label: 'Level one 1',
            children: [
                {
                    id: 4,
                    label: 'Level two 1-1',
                },
            ]
        },
        {
            id: 2,
            label: 'Level one 2',
        },
    ],
    table_data: [],
    // get 0.8 of the window height
    table_height: 100 * parseInt(window.innerHeight * 0.008),
};

let mounted = async function () {
    console.log('mounted xp-admin-jobs-v2.js');
    // fetch jobs from /api/jobs
    let response = await fetch('/api/jobs');
    let response_json = await response.json();
    console.log('response', response_json);
    this.$store.jobs = response_json.jobs ?? [];
    // update level_min
    this.level_min = 0;

    this.table_data = this.$store.jobs;
}

let computed = {
};

let watch = {
    // level_min (val2, val0) {
    //     console.log('level_min', val2, ' <- ' ,val0);
    //     // this.nb_show();
    // }
};

let methods = {
    filter_date (row, column) {
        return row.created.slice(5, 16);
    },
    filter_url (row, column) {
        // build a link from url
        let url = row.url;
        let res = '';
        if (url ?? false) {
            res = '<a href="' + url + '" target="_blank">' + url + '</a>';
        }
        return res;  
    },
    company(job) {
        let res = '';
        if (job.url ?? false) {
            let urlObj = new URL(job.url);
            res = urlObj.pathname.split('/')[this.index_company];
        }
        return res;
    },
    select(job) {
        // convert z to int
        let score = parseInt(job?.z ?? 0);
        // console.log('select', job, score);
        // check if score > level_min
        if (score >= this.level_min) {
            return true;
        }
        return false;
    },
    nb_show() {
        // FIXME: missing inital sync after fetch

        let total = this.$store.jobs.length;
        // get the number of ol.jobs li.job
        let nb = document.querySelectorAll('ol.jobs li.job').length;
        console.log('nb_show', nb, total);
        return nb;
    },
    async act_save($event, job) {
        console.log('act_save', $event, job);
        let target = $event.target;
        let ui_fd = new FormData(target);
        // get z from input
        let z = ui_fd.get('z');
        console.log('user z', z);
        // update job
        job.z = z;

        let json = JSON.stringify(job);
        let form_data = new FormData();
        form_data.append('action', 'update');
        form_data.append('job', json);
        let response = await fetch('/api/jobs', {
            method: 'POST',
            headers: {
            },
            body: form_data,
        });
        let response_json = await response.json();
        console.log('response', response_json);
        // update job list
        this.$store.jobs = response_json.jobs ?? [];

    },
};
export default {
    template: '#template-xp-admin-jobs-v2',
    // WARNING: copy data for each instance
    data: () => Object.assign({}, data),
    mounted,
    methods,
    computed,
    watch,
}