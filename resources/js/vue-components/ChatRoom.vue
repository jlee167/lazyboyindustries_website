<template>
  <chat-window
    :current-user-id="currentUserId"
    :rooms="rooms"
    :messages="messages"
  />
</template>

<script>
import ChatWindow from "vue-advanced-chat";
import "vue-advanced-chat/dist/vue-advanced-chat.css";

export default {
  components: {
    ChatWindow,
  },

  data() {
    return {
      rooms: [
        {
          roomId: 1,
          roomName: "Room 1",
          avatar: "assets/imgs/people.png",
          unreadCount: 4,
          index: 3,
          lastMessage: {
            content: "Last message received",
            senderId: 1234,
            username: "John Doe",
            timestamp: "10:20",
            saved: true,
            distributed: false,
            seen: false,
            new: true,
          },
          users: [
            {
              _id: 1234,
              username: "John Doe",
              avatar: "assets/imgs/doe.png",
              status: {
                state: "online",
                lastChanged: "today, 14:30",
              },
            },
            {
              _id: 4321,
              username: "John Snow",
              avatar: "assets/imgs/snow.png",
              status: {
                state: "offline",
                lastChanged: "14 July, 20:00",
              },
            },
          ],
          typingUsers: [4321],
        },
      ],
      messages: [
        {
          _id: 7890,
          indexId: 12092,
          content: "Message 1",
          senderId: 1234,
          username: "John Doe",
          avatar: "assets/imgs/doe.png",
          date: "13 November",
          timestamp: "10:20",
          system: false,
          saved: true,
          distributed: true,
          seen: true,
          deleted: false,
          failure: true,
          disableActions: false,
          disableReactions: false,
          replyMessage: {
            content: "Reply Message",
            senderId: 4321,
            files: [
              {
                name: "My Replied File",
                size: 67351,
                type: "png",
                audio: true,
                duration: 14.4,
                url: "https://firebasestorage.googleapis.com/...",
                preview: "data:image/png;base64,iVBORw0KGgoAA...",
              },
            ],
          },
        },
      ],
      currentUserId: 1234,
    };
  },
};
</script>
