<html>

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
</head>

<body>
    <img src="https://img.freepik.com/free-vector/hand-drawn-world-animal-day-background-with-animals_23-2149625089.jpg" />
    <img src="https://petimg.ajirangi.com/user_upload_data_preprocess_raw/loss_id/100048/202204081604640.jpg" />
    <div id="root">
        <h2 class="title">File Upload</h2>
        <hr>
        <div class="contents">
            <div class="upload-box">
                <div id="drop-file" class="drag-file">
                    <img src="https://img.icons8.com/pastel-glyph/2x/image-file.png" alt="파일 아이콘" class="image">
                    <p class="message">Drag files to upload</p>
                    <img src="" alt="미리보기 이미지" class="preview">
                </div>
                <label class="file-label" for="chooseFile">Choose File</label>
                <input class="file" id="chooseFile" type="file" multiple onchange="dropFile.handleFiles(this.files)">
            </div>
            <div id="files" class="files">
                <div class="file">
                    <div class="thumbnail">
                        <img src="https://img.icons8.com/pastel-glyph/2x/image-file.png" alt="파일타입 이미지" class="image">
                    </div>
                    <div class="details">
                        <header class="header">
                            <span class="name">Photo.png</span>
                            <span class="size">7.5 mb</span>
                        </header>
                        <div class="progress">
                            <div class="bar"></div>
                        </div>
                        <div class="status">
                            <span class="percent">37% done</span>
                            <span class="speed">90KB/sec</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    let selectedFile = ""

    function DropFile(dropAreaId, fileListId) {
        let dropArea = document.getElementById(dropAreaId);
        let fileList = document.getElementById(fileListId);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            preventDefaults(e);
            dropArea.classList.add("highlight");
        }

        function unhighlight(e) {
            preventDefaults(e);
            dropArea.classList.remove("highlight");
        }

        function handleDrop(e) {
            unhighlight(e);
            let dt = e.dataTransfer;
            let files = dt.files;

            console.log(files.length, selectedFile)

            if (files.length == 0) {
                files = [selectedFile]
            }

            handleFiles(files);

            const filesList = document.getElementById(fileListId);
            if (filesList) {
                filesList.scrollTo({
                    top: filesList.scrollHeight
                });
            }

            selectedFile = ""
        }

        function handleFiles(files) {
            console.log(files)

            files = [...files];
            files.forEach(previewFile);
        }


        function previewFile(file) {
            // fileList.appendChild(renderFile(file));
            renderFile(file);
        }

        /**
         * @param {string} file string type filename or url
         */
        async function renderFile(file) {
            console.log(file.startsWith("http"))

            const reader = new FileReader();

            if (file.startsWith("http")) {
                const r = await fetch(file)
                const blob = await r.blob()
                reader.readAsDataURL(blob);
            } else {
                reader.readAsDataURL(file);
            }

            reader.onloadend = function() {
                let img = dropArea.querySelector(".preview");
                img.src = reader.result;
                img.style.display = "block";
            };
        }

        // function renderFile(file) {
        //     let fileDOM = document.createElement("div");
        //     fileDOM.className = "file";
        //     fileDOM.innerHTML = `
        //   <div class="thumbnail">
        //     <img src="https://img.icons8.com/pastel-glyph/2x/image-file.png" alt="파일타입 이미지" class="image">
        //   </div>
        //   <div class="details">
        //     <header class="header">
        //       <span class="name">${file.name}</span>
        //       <span class="size">${file.size}</span>
        //     </header>
        //     <div class="progress">
        //       <div class="bar"></div>
        //     </div>
        //     <div class="status">
        //       <span class="percent">100% done</span>
        //       <span class="speed">90KB/sec</span>
        //     </div>
        //   </div>
        // `;
        //     return fileDOM;
        // }

        dropArea.addEventListener("dragenter", highlight, false);
        dropArea.addEventListener("dragover", highlight, false);
        dropArea.addEventListener("dragleave", unhighlight, false);
        dropArea.addEventListener("drop", handleDrop, false);

        return {
            handleFiles
        };
    }

    const dropFile = new DropFile("drop-file", "files");

    document.addEventListener("dragenter", (e) => {
        if (e.target.src != undefined) {
            selectedFile = e.target.src
            console.log(selectedFile)
        }
    })
</script>

<style>
    #root {
        width: 100%;
        margin: 0 auto;
        max-width: 800px;
    }

    .title {
        text-align: center;
    }

    .contents {
        display: flex;
        flex-direction: row;
        margin-top: 30px;
    }

    .contents .upload-box {
        width: calc(50% - 15px);
        box-sizing: border-box;
        margin-right: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .contents .upload-box .drag-file {
        width: 100%;
        height: 360px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border: 3px dashed #dbdbdb;
    }

    .contents .upload-box .drag-file.highlight {
        border: 3px dashed red;
    }

    .contents .upload-box .drag-file .image {
        width: 40px;
    }

    .contents .upload-box .drag-file .message {
        margin-bottom: 0;
    }

    .contents .upload-box .file-label {
        margin-top: 30px;
        background-color: #5b975b;
        color: #fff;
        text-align: center;
        padding: 10px 0;
        width: 65%;
        border-radius: 6px;
        cursor: pointer;
    }

    .contents .upload-box .file {
        display: none;
    }

    .contents .files {
        width: calc(50% - 15px);
        box-sizing: border-box;
        overflow: auto;
        height: 360px;
    }

    .contents .files .file {
        display: flex;
        padding: 20px 20px;
        border-bottom: 1px solid #dbdbdb;
    }

    .contents .files .file:last-child {
        margin-bottom: 0px;
        border-bottom: none;
    }

    .contents .files .file .thumbnail {
        display: flex;
        flex: none;
        width: 50px;
        margin-right: 20px;
        align-items: center;
    }

    .contents .files .file .thumbnail .image {
        width: 100%;
    }

    .contents .files .file .details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .contents .files .file .details .header {
        display: flex;
    }

    .contents .files .file .details .header .name {
        width: 100px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .contents .files .file .details .header .size {
        margin-left: auto;
    }

    .contents .files .file .progress {
        position: relative;
        height: 6px;
        background-color: #dbdbdb;
        overflow: hidden;
        margin-top: 4px;
        border-radius: 10px;
    }

    .contents .files .file .progress .bar {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        background-color: #5b975b;
    }

    .contents .files .file .status {
        display: flex;
        width: 100%;
    }

    .contents .files .file .status .percent {}

    .contents .files .file .status .speed {
        margin-left: auto;
    }

    .preview {
        display: none;
        position: absolute;
        left: 0;
        height: 0;
        width: 100%;
        height: 100%;
    }

    @media(max-width: 700px) {
        .contents {
            display: flex;
            flex-direction: column;
            margin-top: 30px;
        }

        .contents .upload-box {
            width: 100%;
            box-sizing: border-box;
            margin-right: 0;
        }

        .contents .upload-box .drag-file {
            height: 150px;
        }

        .contents .files {
            width: 100%;
            box-sizing: border-box;
            margin-right: 0;
            overflow: initial;
        }
    }
</style>

</html>