<template>
  <div>
    <v-card elevation="1" class="comments pa-3 reply">
      <v-card-actions>
        <div class="d-flex justify-start align-center">
          <v-avatar>
            <img :src="comment.avatar">
          </v-avatar>
          <div class="nickname-area ml-3">
            <div class="d-flex flex-column">
              <div>
                <span class="nickname mr-3">{{ comment.nickname }}</span>
                <span v-if="comment.parent">
                  回复
                  <span
                    class="nickname ml-3"
                  >{{ comment.parent.nickname }}:
                  </span>
                </span>
              </div>
              <div class="publish-time">
                {{ comment.created_at }}
              </div>
            </div>
          </div>
        </div>
        <v-spacer />
        <v-btn
          text
          color="primary"
          href="javascript:"
          @click="onReply(comment.id, comment.id)"
        >
          回复
        </v-btn>
      </v-card-actions>
      <v-card-text>
        {{ comment.content }}
      </v-card-text>
    </v-card>
    <div v-for="(sub_comment, k) in comment.children" :key="k" :style="subCommentStyle(sub_comment.level)">
      <comment-tree-item :comment="sub_comment" @onReplyClick="onReplyClick" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'CommentTreeItem',
  props: {
    comment: {
      type: Object,
      default () {
        return []
      }
    }
  },
  methods: {
    onReply (parentId, belong) {
      this.$emit('onReplyClick', {
        parent_id: parentId,
        belong
      })
    },
    onReplyClick (item) {
      this.onReply(item.parent_id, item.belong)
    },
    subCommentStyle (level) {
      return level < 5 ? 'margin-left: 20px' : ''
    }
  }
}
</script>

<style lang="scss" scoped>
  .comment-header {
    height: 50px;
    margin: 10px 0;
    display: flex;
    align-items: center;
  }

  .avatar {
    display: block;
    width: 40px;
    height: 40px;
    float: left;
    border-radius: 20px;
    overflow: hidden;
    background-size: 100%;

    .avatar-img {
      width: 100%;
      height: 100%;
    }
  }

  .nickname-area {
    margin-left: 10px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;

    .nickname {
      color: #ff9800;
      font-size: 15px;
      font-weight: 500;
    }

    .publish-time {
      color: #8f8f8f;
      font-weight: 400;
      font-size: 13px;
    }
  }

  .reply {
    border-top: #aad0f7 dashed 1px;
    font-size: 15px;
    font-weight: 400;
    padding: 8px 16px;
    background-color: var(--v-commentBackground-base);
    border-radius: 4px;
    border-left: 5px solid var(--v-secondary-base);
    margin: 20px 0;
  }
</style>
