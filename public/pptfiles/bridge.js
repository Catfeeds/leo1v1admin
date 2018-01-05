function gotoNextStep() {
    execNext()
}

function gotoPreviousStep() {
    ExecGoBack()
}
function gotoStep(slideIndex, stepIndex, trigger, isBack) {
    syncExec(slideIndex, stepIndex, trigger, isBack)
}
$(document).ready(function () {
    window.onRegistered && window.onRegistered(window._control.length, windows._control[0].animations && windows._control[0].animations.length)
})
