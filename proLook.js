let parts = ["home", "overall", "committee", "breakdown", "sponsor", "faq", "register"];
let displayScrollTop = false;
let sectionPosition = 0;

// 计算主页元素垂直间距
function updateVerticalPadding() {
    let spareHeight = document.body.clientHeight
        - $("section .c-main-logo").height()
        - $("section .c-main-description").height()
        - $("section .borderless.menu").height();
    $(".c-logo-padding").height(spareHeight * 0.33);
    $(".c-description-padding").height(spareHeight * 0.14);
    $(".c-main-menu-padding").height(spareHeight * 0.07);
}

// 计算所在区块
function calcPartPosition() {
    // 默认宽屏，不检测注册部分
    var m = 6;
    if (document.body.clientWidth <= 768) {
        // 手机需要检测注册部分
        m = 7;
    }
    for (let i = 1; i < m; i++) {
        if ($(`#${parts[i]}`).visible(true)) {
            return i;
        }
    }
    return 0;
}

// 高亮所在区块
function updatePartPosition(p) {
    $(".borderless.menu .c-desktop-only.item").removeClass("active");
    $(".vertical.sidebar > .item:not(.header)").removeClass("active");
    if (p != 0) {
        $(`.n-${parts[p]}:not(.icon):not(.button)`).addClass("active");
    }
}

$(document).ready(function () {
    // 主菜单吸附
    $(".borderless.menu").visibility({
        type: "fixed"
    });
    // 侧边菜单展开
    $(".ui.sidebar").sidebar("attach events", ".c-sidebar-toggle");
    // 初始化可折叠文本框
    $('.ui.accordion').accordion();
    // 导航项目滚动动画
    for (let i = 0; i < parts.length; i++) {
        $(`.n-${parts[i]}`).click(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(`#${parts[i]}`).offset().top - 80
            }, 'normal');
            $(".pusher").click();
        });
    }
    // 表单组件初始化
    let calendarOpts = {
        type: 'date',
        formatter: {
            date: function (date, settings) {
                if (!date) return '';
                let day = date.getDate() + '';
                if (day.length < 2) {
                    day = '0' + day;
                }
                let month = (date.getMonth() + 1) + '';
                if (month.length < 2) {
                    month = '0' + month;
                }
                let year = date.getFullYear();
                return year + '/' + month + '/' + day;
            }
        }
    };
    $(".ui.calendar").calendar(calendarOpts);
    $(".ui.selection.dropdown").dropdown();
    $(".ui.radio.checkbox").checkbox();
    // 表单校验
    $('form').form({
        inline: true,
        fields: {
            name: {
                identifier: 'name',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            birthday: {
                identifier: 'birthday',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请选择一个日期'
                    }
                ]
            },
            country: {
                identifier: 'country',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            province: {
                identifier: 'province',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            city: {
                identifier: 'city',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            district: {
                identifier: 'district',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            school: {
                identifier: 'school',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            major: {
                identifier: 'major',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            grade: {
                identifier: 'grade',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            id: {
                identifier: 'id',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'minLength[8]',
                        prompt: '请至少填写{ruleValue}个字符'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            phone: {
                identifier: 'phone',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'number',
                        prompt: '请不要包含非数字字符'
                    },
                    {
                        type: 'minLength[8]',
                        prompt: '请至少填写{ruleValue}个字符'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            im: {
                identifier: 'im',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            github: {
                identifier: 'github',
                optional: true,
                rules: [
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            talent: {
                identifier: 'talent',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            experience: {
                identifier: 'experience',
                optional: true,
                rules: [
                    {
                        type: 'maxLength[200]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            },
            file: {
                identifier: 'cv',
                rules: [
                    {
                        type: 'empty',
                        prompt: '请选择一个文件'
                    }
                ]
            }
        }
    });
    $("input[type=radio][name=hasteam]").change(function () {
        if (this.value == '1') {
            $('form').form('add rule', 'leadername', {
                rules: [
                    {
                        type: 'empty',
                        prompt: '请填写此项'
                    },
                    {
                        type: 'maxLength[40]',
                        prompt: '请不要超过{ruleValue}个字符'
                    }
                ]
            });
        } else {
            $('form').form('remove fields', ['leadername']);
        }
    });
    // 表单异步提交
    $('form').ajaxForm({
        beforeSubmit: function () {
            $('form').attr('class', 'ui double blue loading form');
        },
        success: function (response) {
            $('form').attr('class', 'ui form');
            if (response.succ) {
                $('form').resetForm();
                $('form').form('remove fields', ['leadername']);
                alert('提交成功！你的信息已被记录。');
            } else {
                alert('处理提交时遇到了错误：' + response.msg);
            }
        },
        error: function () {
            $('form').attr('class', 'ui form');
            alert('未知错误！请联系网站管理员。');
        }
    });
    $("input:text, .ui.button", ".ui.action.input").click(function (e) {
        $("input:file", $(e.target).parents(".ui.action.input")).click();
    });
    $("input:file", ".ui.action.input").change(function (e) {
        if (e.target.files.length == 0) {
            $('input:text', $(e.target).parents(".ui.action.input")).val('');
        } else if (e.target.files[0].size > 8388608) {
            alert("文件大小超出了8M的限制！");
            $('input:file', $(e.target).parents(".ui.action.input")).val('');
            $('input:text', $(e.target).parents(".ui.action.input")).val('');
        } else if (e.target.files[0].name.match(/.+\.(pdf|doc|docx)$/) == null) {
            alert("文件格式不是PDF或Word文档！");
            $('input:file', $(e.target).parents(".ui.action.input")).val('');
            $('input:text', $(e.target).parents(".ui.action.input")).val('');
        } else {
            let name = e.target.files[0].name;
            $('input:text', $(e.target).parents(".ui.action.input")).val(name);
        }
    });
    // 计算主页元素垂直间距
    updateVerticalPadding();
    // 淡入动画
    $(".c-main-logo").animate({opacity: "1"}, 750);
    $(".c-main-description").animate({opacity: "1"}, 1000);
    $(".borderless.menu").animate({opacity: "1"}, 1250);
});
// 监听窗口大小改变
$(window).resize(function () {
    updateVerticalPadding();
});
// 监听窗口滚动
$(window).scroll(function () {
    if (document.documentElement.scrollTop + document.body.scrollTop > document.body.clientHeight) {
        // 显示顶部按钮
        if (!displayScrollTop) {
            // 还没显示，触发动画
            $(".ui.circular.icon.button.n-home").animate({right: "1rem"}, 600);
        }
        displayScrollTop = true;
    } else {
        if (displayScrollTop) {
            // 正在显示，触发动画
            $(".ui.circular.icon.button.n-home").animate({right: "-5rem"}, 600);
        }
        displayScrollTop = false;
    }
    // 刷新菜单高亮项
    let t = calcPartPosition();
    if (t !== sectionPosition) {
        updatePartPosition(t);
        sectionPosition = t;
    }
});
