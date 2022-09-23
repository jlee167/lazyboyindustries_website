import PurchaseRecord from '../../models/commerce/PurchaseRecord';
import {postReview as postReviewAPI, getRecords as getRecordsAPI} from "../../api/commerce/commerce";
import PurchaseRecordDTO from '../../models/commerce/PurchaseRecordDTO';


const commerceAPI = {
    postReview: postReviewAPI,
    getRecords: getRecordsAPI,
};


window.purchaseHistoryApp = new Vue({
    el: "#cart",
    data: {
        title: String("My Purchases"),
        items: Array(),
        totalPrice: Number(0),
        discount: Number(0),
        loaded: false,
    },
    methods: {
        postReview: postReview,
        getRecords: getRecords,
    },
});


function postReview(review) {
    commerceAPI.postReview(review)
    .then(() => {
        window.location.reload();
    })
    .catch(err => {
        console.error(err.message);
        window.alert(err.message);
    });
}


function getRecords () {
    commerceAPI.getRecords()
    .then((json) => {
        json.forEach((item) => {
            let recordData = new PurchaseRecordDTO(item);
            this.items.push(new PurchaseRecord(recordData));
        });
        this.loaded = true;
    })
    .catch((err) => {
        console.error(err.message);
        window.alert(err.message);
    });
}



/* -------------------------------- Page Init ------------------------------- */
window.purchaseHistoryApp.getRecords();
