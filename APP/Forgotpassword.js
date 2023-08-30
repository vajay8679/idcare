/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, { Component } from 'react';
import {
  SafeAreaView,
  StyleSheet,
  ScrollView,
  View,
  TextInput,
  StatusBar,
  TouchableOpacity,
  Image,
  AsyncStorage,
  ActivityIndicator
} from 'react-native';
import { NavigationActions, StackActions } from 'react-navigation';
//import LinearGradient from 'react-native-linear-gradient';

//import {} from 'react-navigation';

import { Icon, Container, Header, Content, Footer, FooterTab, Button, Text, Item, Input, Form } from 'native-base';

import Toast, { DURATION } from 'react-native-easy-toast'

const resetAction = StackActions.reset({
  index: 0,
  actions: [NavigationActions.navigate({ routeName: 'Home' })],
});

export default class Forgotpassword extends Component {
  constructor(props) {
    super(props);
    this.state = {
      animate1: false,
      dataSource: [],
      animating: false,
      email: "",
      password: "",
      devicetype: '',
      error: {},
      animate: false,
      selectedDate: "",
      isDateTimePickerVisible: false,
      animate2: false,
      isLoading: false,
      isSignInButtonEnable: false,
    };
  }
  handleforgotpass() {
   

    console.log(this.state.email);
    // console.log(this.state.password);
    this.setState({
      animating: true
    })
    let formdata = new FormData();
    formdata.append("email", this.state.email)

    formdata.append("device_type", 'ANDROID')
    console.log(formdata)

  //  if (this.state.email != '') {
      fetch('http://idcaresteward.com/api/v1/user/forgot_password', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {

        //  alert(responseJson.status)
          if (responseJson.status == 1) {
            this.setState({
              animating: false
            })

            this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
              <Text style={{ color: 'white' }}>
                {responseJson.message}

              </Text>
            </View>);

            //this.props.navigation.goBack();

          }
          else {

            this.setState({
              animating: false
            })
            this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
              <Text style={{ color: 'white' }}>
                {responseJson.message}

              </Text>
            </View>);


          }
          //return responseJson.movies;
        })
        .catch(err => {
          console.log(err)
        })

   // }
    // else {
    //   this.setState({
    //     animating: false
    //   })
    //   this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
    //   <Text style={{ color: 'white' }}>
    //    Please enter email address.

    //   </Text>
    // </View>);
    // }




  }
  goback() {
    // this.props.navigation.navigate('Home');
    // alert(2323)
    this.props.navigation.goBack();
  }

  render() {

    return (

      <Container>


        {this.state.animating == true &&
          <ActivityIndicator
            animating={this.state.animating}
            color='#B22B57'
            size="large"
            style={styles.activityIndicator} />
        }


        <Toast style={{ backgroundColor: 'black' }} ref="toast" fadeOutDuration={3000}  />

        <View style={{ flex: 1, flexDirection: 'column' }}>


          <ScrollView>

            <View style={{ marginTop: '27%', height: '50%', justifyContent: 'center', padding: 5, alignContent: 'center', alignItems: 'center' }}>

              <Image  style={{marginTop:40, resizeMode:'stretch',width:200,height:80}} source={require('./image/logo.png')} ></Image>
              {/* <Icon size={90} name='key' /> */}
              <View style={{ borderRadius: 7, width: '90%', height: 40, paddingLeft: 25, paddingRight: 25, marginTop: 30 }}>
                <Text style={{ paddingBottom: 10, fontSize: 18, textAlign: 'center', color: 'white', paddingTop: 5,color:'#933A63' }}>Forgot Your Password?  </Text>

              </View>
              <View style={{ paddingTop: 20, height: 120 }}>
                <Text style={{ fontSize: 15, paddingLeft: 25, paddingRight: 25, color: '#525252' }}>Please enter your Email so we can send you an email to reset your password.  </Text>
              </View>
            </View>

            <View style={{

              flex: 1, width: '100%', height: '100%', alignSelf: 'center', paddingLeft: 25, paddingRight: 25
            }}>

              {/* <Text style={{fontSize:12,padding:4,color:'#933A63',fontWeight:'bold'}}>Login To Continue</Text> */}
              <View style={{ height: 40, margin: 3, marginBottom: 6, backgroundColor: '#F7F7F7', borderLeftColor: '#AC2C57', borderLeftWidth: 5, borderRadius: 5 }}>
                <Item style={{ paddingLeft: 6 }}>
                  <Icon name='home' />
                  <TextInput
                    style={{ height: 40, width: '100%' }}
                    onChangeText={email => this.setState({ email })}
                    //  onSubmitEditing={() => this.password.focus()}
                    placeholder='Email' />
                </Item>
              </View>


              <TouchableOpacity onPress={this.goback.bind(this)} style={{ flexDirection: 'row', padding: 10, width: '100%', justifyContent: 'flex-end' }}><Text style={{ justifyContent: 'flex-end', fontSize: 12, color: '#933A63',textDecorationLine:'underline' }}>Login here</Text></TouchableOpacity>

              <TouchableOpacity onPress={this.handleforgotpass.bind(this)} style={{ borderRadius: 7, backgroundColor: '#8F3A5D', height: 45, alignItems: 'center', justifyContent: 'center' }}  >

                <Text style={{ color: 'white', fontSize: 18 }}> Send </Text>

              </TouchableOpacity>


            </View>


          </ScrollView>
        </View>




      </Container>


    );
  }
};


var styles = StyleSheet.create({
  linearGradient: {
    flex: 1,
    paddingLeft: 15,
    paddingRight: 15,
    borderRadius: 5
  },
  buttonText: {
    fontSize: 18,
    fontFamily: 'Gill Sans',
    textAlign: 'center',
    margin: 10,
    color: '#ffffff',
    backgroundColor: 'transparent',
  },
  activityIndicator: {
    //  flex: 1,
    position: 'absolute',
    left: 0,
    right: 0,
    top: 0,
    bottom: 0,
    alignItems: 'center',
    justifyContent: 'center',
    //backgroundColor:'#F5FCFF',
    //   backgroundColor: 'rgba(40, 40, 40, 0.5)',
    zIndex: 9999
  }
});