<template>
  <div id="container">
    <h5>{{ category }}</h5>
    <table id="skillTable">
      <thead id="tableHead">
        <tr>
          <th scope="col" role="columnheader" width="30%" class="border-right">
            Skill
          </th>
          <th scope="col" role="columnheader" width="70%">Details</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="(skillItem, index) in skillItems"
          :key="skillItem.name"
          class="skill-entry"
          :class="{ noBottom: index == skillItems.length - 1 }"
        >
          <td class="skill-name border-right" widht="70%">
            <img
              class="skill-image"
              v-if="skillItem.imgUrl"
              :src="skillItem.imgUrl"
            />
            {{ skillItem.name }}
          </td>
          <td class="col">{{ skillItem.description }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    skillItems: Array,
    category: String,
  },

  mounted() {},
};
</script>


<style scoped>
#container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  align-items: flex-start;
}

#skillTable {
  width: 100%;
  height: auto;
  table-layout: fixed;
}

#tableHead {
  height: 40px;
}

#contents {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  flex-grow: 1;
  width: auto;
  height: 100%;
  background-color: grey;
}

tr {
  border-bottom: 1px rgb(160, 158, 158) solid;
}

.noBottom {
  border-bottom: none !important;
}

.border-right {
  border-right: 1px rgb(160, 158, 158) solid;
}

.border-right {
  border-right: 1px rgb(160, 158, 158) solid;
}

tr.skill-entry:hover {
  background-color: rgba(255, 248, 157, 0.7);
}

th {
  text-align: center;
  vertical-align: center;
  color: rgb(46, 45, 45);
}

td {
  padding-left: 10px;
  padding-right: 10px;
  padding-top: 20px;
  padding-bottom: 20px;
  text-align: center;
}

.skill-image {
  height: 40px;
  width: 40px;
}

.skill-name {
  vertical-align: center;
}

.skill-description {
  height: 100px;
  width: auto;
  flex-grow: 1;
}
</style>
