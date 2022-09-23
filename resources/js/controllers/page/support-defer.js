
window.addEventListener('resize', () => {
    $('#summernote').width = window.width - 100;
}, false);



const supportPages = Object.freeze({
    FAQ: "FAQ",
    REQUEST: "REQUEST",
});


let qnaArr = window.strings.FAQ_CONTENTS;
for (qna of qnaArr){
    qna.active = false;
}


window.supportApp = new Vue({
    el: '#main',
    data: {
        pages: supportPages,
        currentPage: supportPages.FAQ,
        qnaArr: qnaArr,
    },

    computed: {
        FAQview: function() {
            return this.currentPage == this.pages.FAQ;
        },

        reqView: function() {
            return this.currentPage == this.pages.REQUEST;
        },

        requestContent: function() {
            return this.$refs.summernote.getContent();
        },
    },

    methods: {
        showFAQ: function() {
            this.currentPage = this.pages.FAQ;
        },

        showRequest: function() {
            this.currentPage = this.pages.REQUEST;
        }
    },
});
