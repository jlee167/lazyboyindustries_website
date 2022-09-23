import * as PeerAPI from "../../api/social/peer";


window.peerApp = new Vue({
    el: '#peer-list-section',

    data: {
        tab: Number(0),
        peers: [],
        activeGroup: [],
        protecteds: [],
        guardians: [],
        sentRequests: [],
        incomingRequests: [],
        guardianUsername: "",
        protectedUsername: "",
    },

    computed: {
        currentPeerTab: function() {
            return this.tab < 2;
        },

        sentTab: function() {
            return this.tab == 3;
        },

        incomingTab: function() {
            return this.tab == 2;
        },

        sentReqEmpty: function() {
            return this.sentRequests.length == 0;
        }
    },

    mounted: function () {
        this.activeGroup = this.protecteds;
    },

    methods: {
        addGuardian: addGuardian,
        addProtected: addProtected,
        respond: respondPeerRequest,
        refreshLists: refreshLists,
    }
});


/**
 * Get updated list of protecteds/guardians/requests from server and
 * register it to application
 */
function refreshLists() {
    this.peers = [];
    this.sentRequests = [];
    this.incomingRequests = [];


    /* ---------------------- Get Pending Requests ---------------------- */
    PeerAPI.getPendingRequests()
    .then((json) => {
        let pendingRequests = json.pendingRequests;
        if (pendingRequests) {
            for (let iter = 0; iter < pendingRequests.length; iter++) {
                let request = {};
                let isPeerGuardian = (pendingRequests[iter].uid_protected == uid);

                /* Determine if current user is the sender */
                let isSent = ((pendingRequests[iter].uid_guardian == uid)
                    && pendingRequests[iter].signed_guardian == "ACCEPTED")
                    || ((pendingRequests[iter].uid_protected == uid)
                        && pendingRequests[iter].signed_protected == "ACCEPTED");

                request.id = pendingRequests[iter].id;
                if (isPeerGuardian) {
                    request.uid = pendingRequests[iter].uid_guardian;
                    request.username = pendingRequests[iter].username_guardian;
                    request.imageUrl = pendingRequests[iter].image_url_guardian;
                    request.guardian = true;
                } else {
                    request.uid = pendingRequests[iter].uid_protected;
                    request.username = pendingRequests[iter].username_protected;
                    request.imageUrl = pendingRequests[iter].image_url_protected;
                    request.protected = true;
                }

                if (isSent) {
                    this.sentRequests.push(request);
                } else {
                    this.incomingRequests.push(request);
                }
            }
        }
    })
    .catch((err) => {
        window.alert("Error:" + err);
    });


    /* ------------------------- Get guardian list ----------------------- */
    PeerAPI.getGuardians()
    .then((json) => {
        json.guardians.forEach((guardian) => {
            this.peers.push({
                uid: guardian.id,
                username: guardian.username,
                image_url: guardian.image_url,
                guardian: true,
            });

            this.guardians.push({
                uid: guardian.id,
                username: guardian.username,
                image_url: guardian.image_url,
            });
        });
    })
    .then(() => {
        return PeerAPI.getProtecteds();
    })
    .catch((err) => {
        window.alert("Error:" + err);
    });

    /* ----------------------- Get protecteds list ---------------------- */
    PeerAPI.getProtecteds()
    .then((json) => {
        json.protecteds.forEach((member) => {
            this.peers.push({
                uid: member.id,
                username: member.username,
                image_url: member.image_url,
                status: member.status,
                streamID: member.streamID,
                protected: true,
            });

            this.protecteds.push({
                uid: member.id,
                username: member.username,
                image_url: member.image_url,
                status: member.status,
                streamID: member.streamID,
            });
        });
    })
    .catch((err) => {
        window.alert("Error:" + err);
    });


    /* --------------------------- Sort lists ------------------------ */
    this.peers.sort((a, b) => {
        return a.uid - b.uid;
    })

    for (let i = 0; i + 1 < this.peers.length; i++) {
        if (this.peers[i].uid === this.peers[i + 1].uid) {
            this.peers[i].assign(this.peers[i + 1]);
            this.peers.splice(i + 1, 1);
        }
    }
}


/**
 * Request specified user to be current user's protected
 *
 * @param {*} username
 */
function addProtected(username) {
    PeerAPI.addProtected(username)
    .then(() => {
        window.location.reload();
    })
    .catch((err) => {
        console.error(`${err.name}: ${err.message}`);
        window.alert(`${err.name}: ${err.message}`);
    })

}


/**
 * Request specified user to be current user's guardian
 *
 * @param {*} username
 */
function addGuardian(username) {
    PeerAPI.addGuardian(username)
    .then(() => {
        window.location.reload();
    })
    .catch((err) => {
        console.error(`${err.name}: ${err.message}`);
        window.alert(`${err.name}: ${err.message}`);
    });
}


/**
 * Accept or decline a pending protected/guardian request
 *
 * @param {*} requestID
 * @param {*} response
 */
function respondPeerRequest(requestID, response) {
    PeerAPI.respondPeerRequest(requestID, response)
    .then(() => {
        window.location.reload();
    })
    .catch((err) => {
        /* Todo */
    });
}



/* --------------------------------- Startup -------------------------------- */
window.peerApp.refreshLists();
