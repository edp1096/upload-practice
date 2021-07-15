package main // import "upload-stream"

import (
	b64 "encoding/base64"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"math/rand"
	"net/http"
	"os"
	"path/filepath"
	"strings"
	"time"

	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"

	"github.com/oklog/ulid/v2"
)

type (
	FileData struct {
		Name    string `json:"name"`
		Type    string `json:"type"`
		Content string `json:"content"`
	}
)

func uploadTMP(c echo.Context) (err error) {
	var file FileData

	dataROOT, err := os.Getwd()
	if err != nil {
		return c.JSON(http.StatusBadRequest, map[string]string{"msg": err.Error()})
	}

	dataPathTMP := dataROOT + "/../data_tmp/"

	if _, err := os.Stat(dataPathTMP); os.IsNotExist(err) {
		err := os.Mkdir(dataPathTMP, 0644)
		if err != nil {
			return c.JSON(http.StatusBadRequest, map[string]string{"msg": err.Error()})
		}
	}

	if b, err := ioutil.ReadAll(c.Request().Body); err == nil {
		if err := json.Unmarshal(b, &file); err != nil {
			fdata, _ := json.Marshal(&file)
			return c.JSON(http.StatusBadRequest, map[string]string{"msg": err.Error(), "file": string(fdata)})
		}
	}

	t := time.Unix(1000000, 0)
	entropy := ulid.Monotonic(rand.New(rand.NewSource(t.UnixNano())), 0)

	fileNAME := strings.TrimSuffix(file.Name, filepath.Ext(file.Name))
	fileEXT := filepath.Ext(file.Name)

	tmpName := fileNAME + fmt.Sprint(ulid.MustNew(ulid.Timestamp(t), entropy)) + "." + fileEXT

	content, _ := b64.StdEncoding.DecodeString(file.Content)
	err = ioutil.WriteFile(dataPathTMP+tmpName, content, 0644)
	if err != nil {
		return c.JSON(http.StatusBadRequest, map[string]string{"msg": err.Error()})
	}

	result := map[string]string{
		"result":   "done",
		"name":     file.Name,
		"type":     file.Type,
		"tmp_name": tmpName,
	}

	return c.JSON(http.StatusOK, result)
}

func main() {
	e := echo.New()

	e.Use(middleware.Logger())
	e.Use(middleware.Recover())

	e.Use(middleware.CORSWithConfig(middleware.CORSConfig{
		AllowOrigins: []string{"*"},
		AllowMethods: []string{"*"},
	}))

	e.Static("/", "public")
	e.POST("/upload-tmp", uploadTMP)

	// e.Logger.Fatal(e.Start(":1323"))
	e.Logger.Fatal(e.Start("localhost:1323"))
}
