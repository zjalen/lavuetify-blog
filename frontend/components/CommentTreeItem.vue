<template>
  <div>
    <div class="comments pa-3 reply">
      <div class="comment-header">
        <div class="avatar">
          <img :src="comment.avatar" class="avatar-img">
        </div>
        <div class="nickname-area">
          <div>
            <span class="nickname">{{ comment.nickname }}</span> <span v-if="comment.parent">回复 <span
              class="nickname"
            >{{ comment.parent.nickname }}:</span></span>
          </div>
          <div class="publish-time">
            {{ comment.created_at }}
          </div>
        </div>
      </div>
      <div>
        <div>
          {{ comment.content }}
          <a
            class="btn-reply"
            href="javascript:"
            @click="onReply(comment.id, comment.id)"
          >点击回复</a>
        </div>
      </div>
    </div>
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
  .comments {
    margin-top: 15px;

    .comment {
      border-top: #aad0f7 dashed 1px;
      margin-bottom: 10px;
    }

    .sub-comment {
      border-top: #aad0f7 dashed 1px;
      margin-left: 30px;
    }
  }

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
      margin: 0 5px;
    }

    .publish-time {
      color: #8f8f8f;
      font-weight: 400;
      font-size: 13px;
    }
  }

  .reply {
    font-size: 15px;
    font-weight: 400;
    padding: 8px 16px;
    background-color: #ecf8ff;
    border-radius: 4px;
    border-left: 5px solid #50bfff;
    margin: 20px 0;
  }
</style>
